import Swal from "sweetalert2";
import axios from 'axios';
import dayjs from 'dayjs';

const steps = ['1', '2', '3'];

const SwalWizard = Swal.mixin({
    confirmButtonText: 'Suivant',
    cancelButtonText: 'Précédent',
    showCloseButton: true,
    progressSteps: steps,
    reverseButtons: true,
});

const registerLesson = async (lessons, date, nbReplacements) => {
    if (lessons.length) {
        console.log(lessons);
        const values = [];
        let currentStep;

        for (currentStep = 0; currentStep < steps.length;) {
            let result = null;
            if (parseInt(steps[currentStep]) === 1) {
                const options = [
                    {
                        id: 'join',
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

                if (result.value === 'join' && nbReplacements.value === 0) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Opération impossible',
                        text: "Vous devez libérer un cours avant de pouvoir vous inscrire à un autre.",
                    })

                    return;
                }

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
                result = await SwalWizard.fire({
                    icon: 'question',
                    title: 'Sélectionnez un cours',
                    showCancelButton: currentStep > 0,
                    cancelButtonText: 'Annuler',
                    currentProgressStep: currentStep,
                    html: generateRadioButton(lessons.filter((l) => l.nbSlots > 0), 'lesson'),
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
            else if (parseInt(steps[currentStep]) === 3) {
                const lessonObject = values.find((v) => v.key === 'lesson')
                const selectedLesson = lessons.find((l) => l.id === parseInt(lessonObject.value))

                result = await SwalWizard.fire({
                    icon: 'warning',
                    title: 'Confirmer votre inscription ?',
                    html: `
                    <p>Vous êtes sur le point de vous inscrire au cours de "${selectedLesson.title}"</p>
                    <p>Confirmer ?</p>
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
                values.push({key: 'date', value: date.id});
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
