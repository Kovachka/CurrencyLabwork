const canvas = document.getElementById('myChart');

function addChart(chartLabels, positions, label) {
    const ctx = canvas.getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: "Курс " + label,
                data: positions,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 199, 182, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 199, 182, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {}
    });
    return myChart;
}

const selector = document.getElementById("currency")
const changeInput = document.getElementById("change")
const displayInfo = document.getElementById("changeOutput")
const today = new Date();
const startDay = new Date();
let days = []
let response = {
    destroy: function () {
        return 0;
    }
};
startDay.setDate(startDay.getDate() - 6);

for (let date = startDay; date <= today; date.setDate(startDay.getDate() + 1)) {
    days.push(`${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`);
}
selector.addEventListener('change', () => {
    changeInput.value = displayInfo.innerText = "";
    response.destroy()
    let value = selector.value
    let [rate, currency] = [JSON.parse(value).rates, JSON.parse(value).cc]
    if (rate !== null && currency !== null)
        response = addChart(days, rate, currency)
});

changeInput.addEventListener('input', () => {
    let value = selector.value
    let rate = JSON.parse(value).rates[6];
    if (changeInput.value !== null) {
        let sum = rate * parseFloat(changeInput.value);
        displayInfo.innerText = `${changeInput.value} ${JSON.parse(value).cc} is ${sum.toFixed(2)} UAH`
    }
})



