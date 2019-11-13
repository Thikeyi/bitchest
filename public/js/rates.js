


var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'line',
    /*
    data: {
        labels: ['Bitcoin', 'Ethereum', 'Ripple', 'Bitcoin Cash', 'Cardano', 'Litecoin', 'NEM', 'Stellar', 'IOTA', 'Dash'],

        datasets: [{
            label: 'Cours',
            data: [12, 19, 3, 5, 2, 3],
        }]
    }
    */
    data: rates
});