import 'axios';

"use strict";

console.log(CASHIER_KEY);

const stripe = Stripe(CASHIER_KEY);

const elements = stripe.elements()
const cardElement = elements.create('card')

cardElement.mount('#card-element')

const form              = document.getElementById('payment-form')
const cardBtn           = document.getElementById('card-button')
const cardHolderName    = document.getElementById('card-holder-name')
const params            = (new URL(location)).searchParams;

console.log(params.get('plan'), location.href);


form.addEventListener('submit', (e) => {
    e.preventDefault();

    fetch( `/api/plan/validate/${params.get('plan')}`, {
        headers: {
            'Content-Type': 'application/json',
        },
    } )
    .then(response => response.json())
    .then((data) => {
        
        console.log(data['status']);

        if ( data['status'] ) {

            subscribe();
            
        } else {
            alert("Invalid plan");

            cardBtn.disabled = true;
            return false;
        }
    })
    .catch(err => console.error(err));
});

const subscribe = async(e) => {

    cardBtn.disabled = true

    const { setupIntent, error } = await stripe.confirmCardSetup(
        cardBtn.dataset.secret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardHolderName.value
                }
            }
        }
    )

    if(error) {
        cardBtn.disable = false
    } else {
        let token = document.createElement('input')

        token.setAttribute('type', 'hidden')
        token.setAttribute('name', 'token')
        token.setAttribute('value', setupIntent.payment_method)

        form.appendChild(token)

        form.submit();
    }
}