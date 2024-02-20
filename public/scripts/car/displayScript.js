// If i change page and i back in page : timeoutId = is defined * 2 it's my unique solution atm 
if (typeof timeoutId === 'undefined') {
    var timeoutId;
}

//Trigger Ajax / Display
function toggleEvent(event) {
    let eventElement = event.target;
    let valueDiv = eventElement.value;
    let displayElementValue = document.querySelector('#price-value');
    clearTimeout(timeoutId);
    displayElementValue.textContent = valueDiv + ' €';
    timeoutId = setTimeout(function () {



        if (valueDiv !== null && valueDiv !== undefined) {
            handleAjaxRequest(valueDiv);
        }
    }, 300)
}
// Manage Ajax
function handleAjaxRequest(valueDiv) {

    axios.post('/filter', {
        'price': valueDiv
    })
        .then(function (response) {
            console.log(response);
            updateDisplay(response.data.cars, response.data.imageUrls);
        })
        .catch(function (error) {

            console.error("Erreur lors de la requête AJAX", error);
        });
}
// update display with true db value
function updateDisplay(cars, imageUrls) {
    let displayCar = document.querySelector('#display-car');
    displayCar.innerHTML = '';
    cars.forEach(function (car) {

        let carElement = document.createElement('div');
        carElement.className = 'd-flex flex-column align-items-center flex-grow rounded-4 col-12 col-xl-5 b-gold p-3 m-3 bg-dark';

        // Ajoutez cette ligne pour récupérer l'URL de l'image
        let urlImg = imageUrls[cars.indexOf(car)];
        carElement.innerHTML = `
        <div class="w-100 d-flex flex-column flex-grow justify-content-center align-item-center">
        <img src="${urlImg}" class="w-100 rounded-4 border border-2 border-rounded-5 border-dark mb-3" style="object-fit: cover;">
        <div class="d-flex flex-column px-2">
            <p class="fs-4 mb-5">${car.model}</p>
            <p class="fs-4 mb-5" style="word-wrap: break-word;">${car.description}</p>
            <p class="fs-4 text-center py-1">${new Date(car.yearOfManufacture).toLocaleDateString()}</p>
            <p class="fs-4 text-center py-1">${car.mileage} km</p>
            <p class="fs-4 text-center py-1 mb-5">${car.price} €</p>
            </div>
        </div>
    
        <div class="d-flex flex-column align-items-center text-center w-100">
            <a href='#' class="text-start goldHover mb-3 fs-4" onclick="redirectToContact('${car.model}')">Contact</a>
            <button class="btn-gold w-50" onclick="redirectToGallery(${car.id})">Détails</button>
        </div>
        `;

        displayCar.appendChild(carElement);
    });
}

function searchEvent() {
    let searchValue = document.querySelector('#search').value;
    let displayElementValue = document.querySelector('#search-value');
    displayElementValue.textContent = "Recherche: " + searchValue;

    // Déclencher la requête AJAX de recherche
    handleSearchRequest(searchValue);
}

function handleSearchRequest(searchValue) {
    axios.post('/search', {
        'search': searchValue
    })
    .then(function (response) {
        console.log(response);
        updateDisplay(response.data.cars, response.data.imageUrls);
    })
    .catch(function (error) {
        console.error("Erreur lors de la requête AJAX de recherche", error);
    });
}

function redirectToGallery(carId) {

    let galleryUrl = "/car/gallery/" + carId;

    window.location.href = galleryUrl;
}

function redirectToContact(model) {
    // Utilisez JavaScript pour rediriger vers la route contact.form avec le modèle pré-rempli
    window.location.href = `/contact/form?objet=${model}`;
}