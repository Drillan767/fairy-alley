import Swal from "sweetalert2";
import axios from 'axios';

const steps = ['1', '2', '3', '4'];

const SwalWizard = Swal.mixin({
    confirmButtonText: 'Suivant',
    cancelButtonText: 'Précédent',
    progressSteps: steps,
    reverseButtons: true,
});

let currentStep = 0;
/*
Ideas:
- Finish cleanup
- Find a way to retrieve the lesson object that was picked.
 */

const confirmLeave = async (lesson) => {
    const { isConfirmed } = await SwalWizard.fire({
        icon: 'warning',
        title: 'Confirmer votre absence ?',
        text: `
            Vous êtes sur le point de laisser votre place pour le cours de "${lesson.title}"
            Confirmer ?
            `,
        confirmButtonText: 'Confirmer',
        showCancelButton: true,
        cancelButtonText: 'Annuler'
    })

    if (isConfirmed) return isConfirmed
};

const cancelLesson = async () => {
    const dateList = new Promise((resolve => {
        axios.post(route('user-lesson-date'))
            .then((response) => {
                resolve(response.data)
            })
    }))

    const {value: date} = await SwalWizard.fire({
        icon: 'question',
        title: 'Quel cours souhaitez-vous annuler ?',
        input: 'select',
        inputOptions: dateList,
        inputValidator: (value) => {
            if (!value) {
                return 'Veuillez sélectionner une date';
            }
        }
    });

    if (date) return date;
}

const registerLesson = async (lessons, date) => {
    // Cancel next lesson or subscribe to another?
    if (lessons.length) {
        const values = [];

        let currentStep;

        for (currentStep = 0; currentStep < steps.length;) {
            let result = null;
            if (parseInt(steps[currentStep]) === 1) {
                // StayOrGo
                const options = [
                    {
                        id: 'leave',
                        title: 'Annuler ma présence au cours de cette date',
                    },
                    {
                        id: 'switch lesson',
                        title: "M'inscrire à un cours"
                    }
                ]
                result = await SwalWizard.fire({
                    icon: 'question',
                    title: 'Que voulez-vous faire ?',
                    showCancelButton: currentStep > 0,
                    html: generateRadioButton(options, 'stayOrGo'),
                    currentProgressStep: currentStep,
                    preConfirm: () => {
                        const checked = Swal
                            .getPopup()
                            .querySelector('[name="stayOrGo"]')
                            .value

                        if (!checked) {
                            Swal.showValidationMessage('Veuillez faire un choix.')
                        }

                        return checked
                    }
                })

                if (result.value === 'leave') {
                    values.push((result.value))
                    let initialLesson = lessons.find((l) => l.subscribed === true);
                    if (!initialLesson) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Opération impossible',
                            text: "Vous n'êtes inscrites à aucun des cours du jour sélectionné",
                        })

                        return false;
                    } else {
                        result = await SwalWizard.fire({
                            icon: 'warning',
                            showCancelButton: true,
                            currentProgressStep: steps.length,
                            title: 'Confirmer votre annulation ?',
                            text: `
                                Vous êtes sur le point de laisser votre place pour le cours de "${initialLesson.title}"
                                Confirmer ?
                                `,

                        })
                    }
                }
            } else if (parseInt(steps[currentStep]) === 2) {
                const options = [
                    {
                        id: 'forfeit my place',
                        title: "Je laisse ma place, même s'il peut ne pas y avoir assez de place dans le cours qui m'intéresse",
                    },
                    {
                        id: 'keep my place',
                        title: "Je conserve ma place et me positionne en liste d'attente. Je recevrais un email lorsque qu'une place se sera libérée."
                    }
                ]

                result = await SwalWizard.fire({
                    icon: 'question',
                    currentProgressStep: currentStep,
                    title: 'Que voulez-vous faire ?',
                    showCancelButton: currentStep > 0,
                    html: `<p>Vous allez être placé(e) en file d'attente. Vous pouvez soit garder votre place dans votre cours actuel
                jusqu'à ce qu'une place se libère dans le cours que vous avez choisi, soit libérer votre place en prenant
                le risque qu'aucune place ne se libère pour le cours que vous avez sélectionné.</p> <br />
                ` + generateRadioButton(options, 'decision').outerHTML,
                    preConfirm: () => {
                        const checked = Swal
                            .getPopup()
                            .querySelector('[name="decision"]')
                            .value

                        if (!checked) {
                            Swal.showValidationMessage('Veuillez faire un choix.')
                        }

                        return checked
                    }
                });
            } else if (parseInt(steps[currentStep]) === 3) {
                result = await SwalWizard.fire({
                    icon: 'question',
                    title: 'Sélectionnez un cours',
                    showCancelButton: currentStep > 0,
                    cancelButtonText: 'Annuler',
                    currentProgressStep: currentStep,
                    html: generateRadioButton(lessons, 'lesson'),
                    preConfirm: () => {
                        const checked = Swal
                            .getPopup()
                            .querySelector('[name="lesson"]')
                            .value

                        if (!checked) {
                            Swal.showValidationMessage('Veuillez sélectionner un cours.')
                        }

                        return checked
                    }
                })
            } else if (parseInt(steps[currentStep]) === 4) {
                result = await SwalWizard.fire({
                    icon: 'warning',
                    title: 'Confirmer votre absence ?',
                    text: `
                    Vous êtes sur le point de laisser votre place pour le cours de "${lesson.title}"
                    Confirmer ?
                    `,
                    confirmButtonText: 'Confirmer',
                    showCancelButton: true,
                    cancelButtonText: 'Annuler'
                })
            }

            else {
                break;
            }

            if (result.value) {
                values[currentStep] = result.value
                console.log(values)
                currentStep++
            } else if (result.dismiss === 'cancel') {
                currentStep--;
            } else {
                break
            }

            if (currentStep === steps.length) {
                await Swal.fire(JSON.stringify(values))
            }
        }















        const request = await initialRequest()

        if (request.isDismissed || request.isDenied) {
            return false;
        }

        console.log(request);

        if (request === 'leave') {
            currentStep = 3;
            const userLesson = lessons.find((l) => l.subscribed === true)
            if (userLesson && await confirmLeave(userLesson)) {

                return {
                    request: request,
                    date: date.id,
                    lesson: userLesson.id,
                }
            } else {
                await Swal.fire({
                    icon: 'error',
                    title: 'Opération impossible',
                    text: "Vous n'êtes inscrites à aucun des cours du jour sélectionné",
                })
            }
        } else {
            currentStep++;
            const filtered = lessons.filter((l) => !l.subscribed)
            console.log(filtered)
            const lesson = await selectLesson({filtered})
            currentStep++
            const lastStand = await stayOrGo()
            currentStep++
            const cancelledLesson = await cancelLesson()
            currentStep = 0;

            return {
                request: request,
                targetLesson: lesson,
                cancelledLesson: cancelledLesson,
                behavior: lastStand,
            }
        }
    } else {
        await Swal.fire({
            icon: 'error',
            title: 'Aucun cours disponible',
            text: "La date que vous avez sélectionné ne dispose d'aucun cours, veuillez choisir une autre date."
        })
    }
};

const generateRadioButton = (lessons, name) => {
    let form = document.createElement('form')

    for (let key in lessons) {
        let div = document.createElement('div')
        div.classList.add('inputGroup')

        let input = document.createElement('input')
        input.setAttribute('id', `radio-${lessons[key].id}`)
        input.setAttribute('name', name)
        input.setAttribute('type', 'radio')
        input.setAttribute('value', lessons[key].id)

        let label = document.createElement('label')
        label.setAttribute('for', `radio-${lessons[key].id}`)
        const text = document.createTextNode(lessons[key].title)
        label.appendChild(text)

        div.appendChild(input)
        div.appendChild(label)

        form.appendChild(div)
    }

    return form
}

export default registerLesson
