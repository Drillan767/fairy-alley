import Swal from "sweetalert2";
import axios from 'axios';
import dayjs from 'dayjs';

const steps = ['1', '2', '3', '4', '5'];

const SwalWizard = Swal.mixin({
    confirmButtonText: 'Suivant',
    cancelButtonText: 'Précédent',
    progressSteps: steps,
    reverseButtons: true,
});

const registerLesson = async (lessons, date) => {
    if (lessons.length) {
        const values = [{key: 'date', value: date.id}];
        let currentStep;

        for (currentStep = 0; currentStep < steps.length;) {
            let result = null;
            if (parseInt(steps[currentStep]) === 1) {
                const options = [
                    {
                        id: 'switch lesson',
                        title: "M'inscrire à un cours"
                    },
                    {
                        id: 'leave',
                        title: 'Annuler ma présence au cours de cette date',
                    },
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
                            .querySelector('[name="stayOrGo"]:checked');

                        if (!checked) {
                            Swal.showValidationMessage('Veuillez faire un choix.');
                        }

                        return checked?.value
                    }
                })
                result.key = 'action';

                if (result.value === 'leave') {
                    values.push((result.value));
                    let initialLesson = lessons.find((l) => l.subscribed === true);
                    if (!initialLesson) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Opération impossible',
                            text: "Vous n'êtes inscrites à aucun des cours du jour sélectionné",
                        })

                        return;
                    } else {
                        result = await SwalWizard.fire({
                            icon: 'warning',
                            showCancelButton: true,
                            currentProgressStep: steps.length - 1,
                            title: 'Confirmer votre annulation ?',
                            text: `
                                Vous êtes sur le point de laisser votre place pour le cours de "${initialLesson.title}"
                                Confirmer ?
                                `,
                        })

                        if (result.isConfirmed) {
                            return [
                                {
                                    key: 'action',
                                    value: 'leave',
                                },
                                {
                                    key: 'date',
                                    value: date.id,
                                }
                            ];
                        }
                    }
                }
            }
            else if (parseInt(steps[currentStep]) === 2) {
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
                            .querySelector('[name="decision"]:checked')

                        if (!checked) {
                            Swal.showValidationMessage('Veuillez faire un choix.')
                        }

                        return checked?.value
                    }
                });

                result.key = 'decision';
            }
            else if (parseInt(steps[currentStep]) === 3) {
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
                            .querySelector('[name="lesson"]:checked')

                        if (!checked) {
                            Swal.showValidationMessage('Veuillez sélectionner un cours.')
                        }

                        return checked?.value
                    }
                })

                result.key = 'lesson';

            }
            else if (parseInt(steps[currentStep]) === 4) {

                const hasLessonThayDay = lessons.find((l) => l.subscribed === true)
                if (hasLessonThayDay) {
                    await SwalWizard.fire({
                        icon: 'info',
                        title: 'Information',
                        text: 'Vous allez être désinscrit(e) du cours que vous alliez avoir le ' + dayjs(date.id).format('DD/MM/YYYY'),
                        showCancelButton: currentStep > 0,
                        cancelButtonText: 'Annuler',
                        currentProgressStep: currentStep,
                    })

                    result = {value: date.id}
                } else {
                    const dateList = new Promise((resolve => {
                        axios.post(route('user-lesson-date'))
                            .then((response) => {
                                resolve(response.data)
                            })
                    }))

                    result = await SwalWizard.fire({
                        icon: 'question',
                        title: 'Quel cours souhaitez-vous annuler ?',
                        showCancelButton: currentStep > 0,
                        cancelButtonText: 'Annuler',
                        input: 'select',
                        inputOptions: dateList,
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Veuillez sélectionner une date';
                            }
                        }
                    });
                }

                result.key = 'cancel';
            }
            else if (parseInt(steps[currentStep]) === 5) {
                const lessonObject = values.find((v) => v.key === 'lesson')
                const selectedLesson = lessons.find((l) => l.id === parseInt(lessonObject.value))

                result = await SwalWizard.fire({
                    icon: 'warning',
                    title: 'Confirmer votre absence ?',
                    text: `
                    Vous êtes sur le point de laisser votre place pour le cours de "${selectedLesson.title}"
                    Confirmer ?
                    `,
                    confirmButtonText: 'Confirmer',
                    showCancelButton: true,
                    cancelButtonText: 'Annuler'
                })

                result.key = 'confirm';
            }

            else {
                break;
            }

            if (result.value) {
                values[currentStep] = {key: result.key, value: result.value};
                currentStep++;
            } else if (result.dismiss === 'cancel') {
                currentStep--;
            } else {
                return;
            }

            if (currentStep === steps.length) {
                return values;
            }
        }
    } else {
        await Swal.fire({
            icon: 'error',
            title: 'Aucun cours disponible',
            text: "La date que vous avez sélectionné ne dispose d'aucun cours, veuillez choisir une autre date."
        });
    }
};

const generateRadioButton = (lessons, name) => {
    let form = document.createElement('form');

    for (let key in lessons) {
        let div = document.createElement('div');
        div.classList.add('inputGroup');

        let input = document.createElement('input');
        input.setAttribute('id', `radio-${lessons[key].id}`);
        input.setAttribute('name', name);
        input.setAttribute('type', 'radio');
        input.setAttribute('value', lessons[key].id);

        let label = document.createElement('label');
        label.setAttribute('for', `radio-${lessons[key].id}`);
        const text = document.createTextNode(lessons[key].title);
        label.appendChild(text);

        div.appendChild(input);
        div.appendChild(label);

        form.appendChild(div);
    }

    return form;
}

export default registerLesson
