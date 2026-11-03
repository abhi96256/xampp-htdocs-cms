// Company Incentive Calculation
function calculateCompanyIncentive() {
    let actual_amount = parseFloat(document.querySelector('#actual_amount') ? document.querySelector('#actual_amount').value : 0) || 0;
    let agreement_value = parseFloat(document.querySelector('#agreement_value') ? document.querySelector('#agreement_value').value : 0) || 0;
    let base_amount = actual_amount > 0 ? actual_amount : agreement_value;

    let brokerage_percent = parseFloat(document.querySelector('#brokerage_percent').value) || 0;
    
    let brokerage_amount = document.querySelector('#brokerage_amount');
    if (brokerage_amount) {
        if (brokerage_percent > 0 && base_amount > 0) {
            let total_incentive = (brokerage_percent / 100 * base_amount);
            brokerage_amount.value = total_incentive.toFixed(2);
        } else if (document.activeElement && document.activeElement.id === 'brokerage_percent') {
            // Only clear it if the user is actively emptying the percent field
            brokerage_amount.value = ''; 
        }
        // Trigger calculation for all employee incentives as the base amount changed
        recalculateAllEmployeeIncentives();
        
        // Dispatch event so other listeners (like Net Incentive displayed on page) can update
        if (typeof window.Event === "function") {
            brokerage_amount.dispatchEvent(new Event('input', { bubbles: true }));
        }
        
        // Also just update Net Incentive directly if it exists, to be safe
        let pass_on = parseFloat(document.querySelector('#pass_on_to_customer') ? document.querySelector('#pass_on_to_customer').value : 0) || 0;
        let inc_amt = parseFloat(brokerage_amount.value) || 0;
        let net_disp = document.querySelector('#net_incentive_display');
        if (net_disp) net_disp.innerText = (inc_amt - pass_on).toFixed(2);
    }
}

// Bind events using jQuery to properly catch Select2 changes
if (typeof jQuery !== 'undefined') {
    jQuery(document).on('input change', '#brokerage_percent, #agreement_value, #actual_amount, #pass_on_to_customer', function () {
        calculateCompanyIncentive();
    });

    jQuery(document).on('input change', '.incentive_employee_percent, .incentive_tds_percent', function (e) {
        let row = e.target.closest('[data-x-group]');
        if (row) calculateEmployeeIncentive(row);
    });

    jQuery(document).on('change', '.incentive_user_select', function (e) {
        let row = e.target.closest('[data-x-group]');
        if (row) calculateEmployeeIncentive(row);
    });
} else {
    // Fallback if jQuery is not loaded
    document.addEventListener('input', (e) => {
        if (e.target.id === 'brokerage_percent' || e.target.id === 'agreement_value' || e.target.id === 'actual_amount' || e.target.id === 'pass_on_to_customer') {
            calculateCompanyIncentive();
        }
        if (e.target.classList.contains('incentive_employee_percent')) {
            let row = e.target.closest('[data-x-group]');
            if (row) calculateEmployeeIncentive(row);
        }
    });

    document.addEventListener('change', (e) => {
        if (e.target.id === 'brokerage_percent' || e.target.id === 'agreement_value' || e.target.id === 'actual_amount' || e.target.id === 'pass_on_to_customer') {
            calculateCompanyIncentive();
        }
        if (e.target.classList.contains('incentive_employee_percent') || e.target.classList.contains('incentive_tds_percent') || e.target.classList.contains('incentive_user_select')) {
            let row = e.target.closest('[data-x-group]');
            if (row) calculateEmployeeIncentive(row);
        }
    });
}

function calculateEmployeeIncentive(row) {
    let percent_input = row.querySelector('.incentive_employee_percent');
    let amount_input = row.querySelector('.incentive_employee_amount');
    let tds_percent_input = row.querySelector('.incentive_tds_percent');
    let tds_amount_input = row.querySelector('.incentive_tds_amount');

    if (percent_input && amount_input) {
        let percent = parseFloat(percent_input.value) || 0;
        
        let base_amount_el = document.querySelector('#brokerage_amount');
        let pass_on_el = document.querySelector('#pass_on_to_customer');
        
        let company_incentive = (base_amount_el ? parseFloat(base_amount_el.value) : 0) || 0;
        let pass_on = (pass_on_el ? parseFloat(pass_on_el.value) : 0) || 0;
        
        let base_amount = company_incentive - pass_on;
        
        let gross_incentive = percent / 100 * base_amount;

        if (tds_percent_input && tds_amount_input) {
            let tds_percent = parseFloat(tds_percent_input.value) || 0;
            let tds_amount = gross_incentive * (tds_percent / 100);
            tds_amount_input.value = tds_amount.toFixed(2);
            
            // Deduct TDS from the final Incentive Amount (Net Pay)
            amount_input.value = (gross_incentive - tds_amount).toFixed(2);
        } else {
            amount_input.value = gross_incentive.toFixed(2);
        }
    }
}

function recalculateAllEmployeeIncentives() {
    document.querySelectorAll('[data-x-group]').forEach(row => {
        calculateEmployeeIncentive(row);
    });
}
