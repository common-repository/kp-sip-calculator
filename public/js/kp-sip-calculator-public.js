jQuery(document).ready(function($) {
        
    var myPieChart;
    
    function calculateSIP() {
        var monthlyInvestment = parseFloat($('#sip-monthly-investment').val());
        var returnRate = parseFloat($('#sip-return-rate').val()) / 100;
        var timePeriod = parseInt($('#sip-time-period').val());
        
        var months = timePeriod * 12;
        var investedAmount = monthlyInvestment * months;
        var estimatedReturns = monthlyInvestment * (((Math.pow(1 + returnRate / 12, months) - 1) / (returnRate / 12)) * (1 + returnRate / 12));
        var totalValue = estimatedReturns;
        var returns = totalValue - investedAmount;
        
        $('#sip-invested-amount').text(KPSIPCALCULATOR.currency+Math.round(investedAmount).toLocaleString('en-IN'));
        $('#sip-estimated-returns').text(KPSIPCALCULATOR.currency+Math.round(returns).toLocaleString('en-IN'));
        $('#sip-total-value').text(KPSIPCALCULATOR.currency+Math.round(totalValue).toLocaleString('en-IN'));
        
        // Destroy existing chart if it exists
        if (myPieChart) {
            myPieChart.destroy();
        }

        // Pie Chart
        var ctx = document.getElementById('kp-sip-calculator-pie-chart').getContext('2d');
        myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Invested Amount', 'Estimated Returns'],
                datasets: [{
                    data: [investedAmount, returns],
                    backgroundColor: ['#eef0ff', '#5367ff']
                }]
            },
            options: {
                responsive: true
            }
        });
    }                               
    
    $('#sip-monthly-range').on('input', function() {
        $('#sip-monthly-investment').val(this.value);
        calculateSIP();
    });
    
    $('#sip-return-range').on('input', function() {
        $('#sip-return-rate').val(this.value);
        calculateSIP();
    });
    
    $('#sip-time-range').on('input', function() {
        $('#sip-time-period').val(this.value);
        calculateSIP();
    });

    // Link number inputs to range sliders
    $('#sip-monthly-investment').on('input', function() {
        $('#sip-monthly-range').val(this.value);
        calculateSIP();
    });
    
    $('#sip-return-rate').on('input', function() {
        $('#sip-return-range').val(this.value);
        calculateSIP();
    });
    
    $('#sip-time-period').on('input', function() {
        $('#sip-time-range').val(this.value);
        calculateSIP();
    });
    
    // Initial Calculation
    calculateSIP();
});