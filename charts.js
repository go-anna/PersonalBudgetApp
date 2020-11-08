let incomesChart = document.getElementById('incomeChart').getContext('2d');

Chart.defaults.global.defaultFontColor = '#111';
Chart.defaults.global.defaultFontFamily = "'Lora', sans-serif";

let incomeChart = new Chart(incomesChart, {
    type: 'pie',
    data: {
        labels: ['Salary', 'Bank interest', 'Sales', 'Other'],
        datasets: [{
            label: 'Incomes',
            data: [3000.00, 12.64, 120.00, 250.00],
            backgroundColor: [
                'rgb(0, 102, 153)',
				'rgb(0, 255, 204)',
                'rgb(0, 204, 102)',
                'rgb(0, 153, 0)'
            ],
            borderColor: [
                'rgb(0, 68, 102)',
                'rgb(0, 204, 163)',
                'rgb(0, 179, 89)',
                'rgb(0, 128, 0)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        title:{
			display: true,
			text: 'Summary of your incomes by categoty',
			fontSize: 18,
			fontStyle: 'normal'
		},
		legend:{
			display: true,
			position: 'right',
		},
		layout:{
			padding:{
				top: 30
			}
		},
    }
});

let expenesChart = document.getElementById('expenseChart').getContext('2d');

Chart.defaults.global.defaultFontColor = '#111';
Chart.defaults.global.defaultFontFamily = "'Lora', sans-serif";

let expenseChart = new Chart(expenesChart, {
    type: 'pie',
    data: {
        labels: ['Food', 'Housing costs', 'Transportation', 'Telecommunication', 'Hygiene', 'Travel', 'Education', 'Books'],
        datasets: [{
            label: 'Expanses',
            data: [340.00, 650.00, 23.50, 23.65, 13.20, 120.00, 152.00, 54.00],
            backgroundColor: [
                'rgb(230, 0, 0)',
				'rgb(255, 102, 0)',
                'rgb(204, 0, 153)',
                'rgb(255, 204, 0)',
				
				'rgb(255, 204, 204)',
				'rgb(153, 102, 255)',
                'rgb(255, 0, 102)',
                'rgb(153, 51, 51)'
            ],
            borderColor: [
                'rgb(204, 0, 0)',
                'rgb(230, 92, 0)',
                'rgb(179, 0, 179)',
                'rgb(230, 184, 0)',
				
				'rgb(255, 179, 179)',
				'rgb(136, 77, 255)',
                'rgb(230, 0, 92)',
                'rgb(134, 45, 45)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        title:{
			display: true,
			text: 'Summary of your expanses by categoty',
			fontSize: 18,
			fontStyle: 'normal'
			
		},
		legend:{
			display: true,
			position: 'right',
		},
		layout:{
			padding:{
				top: 30
			}
		},
    }
});