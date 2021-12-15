import Macy from 'macy';

function macy() {
    Macy({
        container: '#macy',
        trueOrder: false,
        waitForImages: false,
        margin: 24,
        columns: 5,
        breakAt: {
            1200: 3,
            520: 2,
            400: 1
        }
    });
}

function contact() {
    const form = document.getElementById('contact');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const feedback = document.querySelector('.feedback')
        feedback.classList.add('hidden');

        let formData = new FormData();
        ['name', 'email', 'subject', 'message'].forEach((entry) => {
            formData.append(entry, document.getElementById(entry).value)
        })

        if (document.getElementById('newsletter').checked) {
            formData.append('newsletter', '1')
        }

        axios.post('/contact', formData)
            .then((response) => {
                feedback.classList.remove('hidden');
                feedback.classList.add('bg-green-100',  'border-green-500', 'text-green-700');
                feedback.querySelector('p').innerHTML = response.data;
                form.reset();
            })
            .catch((error) => {
                if (error.response) {
                    feedback.classList.remove('hidden');
                    feedback.classList.add('bg-red-100',  'border-red-500', 'text-red-700');
                    const errors = error.response.data.errors
                    let messages = '<ul>'
                    for (const property in errors) {
                        messages += `<li>${property}: ${errors[property]}</li>`;
                    }

                    messages += '</li>';
                    feedback.querySelector('p').innerHTML = messages;
                }
            })
    })
}

export { macy, contact };
