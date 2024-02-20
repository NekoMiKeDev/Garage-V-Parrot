function prefillObjectFromURL() {
    // Récupérez le paramètre d'URL 'objet'
    const urlParams = new URLSearchParams(window.location.search);
    const objetValue = urlParams.get('objet');

    // Pré-remplissez le champ d'objet avec la valeur récupérée
    const objetInput = document.getElementById('contact_objet');
    if (objetValue && objetInput) {
        objetInput.value = objetValue;
    }
}

document.addEventListener('DOMContentLoaded', prefillObjectFromURL);
