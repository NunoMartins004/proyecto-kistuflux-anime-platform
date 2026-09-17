// 1. Forzamos la detección del plan desde la URL actual
const paramsActuales = new URLSearchParams(window.location.search);
const planDetectado = paramsActuales.get('plan');

// 2. Definimos el precio final asegurándonos de que sea un String
let montoACobrar = "5.99"; 

if (planDetectado === 'anual') {
    montoACobrar = "59.99";
}

// 3. Renderizamos el botón usando la variable que acabamos de calcular
paypal.Buttons({
    style: {
        layout: 'vertical',
        color:  'gold', 
        shape:  'rect',
        label:  'paypal'
    },
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                description: "KitsuFlux Premium - " + (planDetectado === 'anual' ? "Anual" : "Mensual"),
                amount: {
                    currency_code: "EUR",
                    value: montoACobrar // <--- Aquí ya tendrá el valor 59.99 si es anual
                }
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            fetch('phpConsultas/procesar_pago.php', {
                method: 'POST',
                headers: { 'content-type': 'application/json' },
                body: JSON.stringify({ 
                    orderID: data.orderID,
                    monto: montoACobrar // Enviamos el monto real al PHP para el registro
                })
            }).then(() => {
                alert('¡Pago de ' + montoACobrar + '€ completado con éxito!');
                window.location.href = "../paginaPrincipal/paginaPrincipal.php";
            });
        });
    }
}).render('#paypal-button-container');