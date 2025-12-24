(function ($, elementor) {
    'use strict';

    var widgetCCalculator = function ($scope, $) {
        const $customCalculator = $scope.find('.penci-advcal');
        const $settings = $customCalculator.data('settings');

        if (!$customCalculator.length) {
            return;
        }

        /**
         * Get variable data array
         */
        function getVariableDataArray() {
            const data = [];
            const onlyValueArray = [];
            const radioNameArrayStack = []; // To avoid duplicate radio button values
            let formulaString = "";

            const $fields = $($settings.id).find(
                ".penci-advcal-field-wrap input[type=text], " +
                ".penci-advcal-field-wrap input[type=hidden], " +
                ".penci-advcal-field-wrap input[type=checkbox], " +
                ".penci-advcal-field-wrap input[type=radio], " +
                ".penci-advcal-field-wrap input[type=number], " +
                ".penci-advcal-field-wrap select"
            );

            $fields.each(function (index, item) {
                const $item = $(item);
                const itemType = $item.prop("type");
                const itemValue = parseFloat($item.val());
                const variableIndex = index + 1;

                if (itemType === "radio") {
                    const radioName = $item.attr('name');
                    if ($(`input[name='${radioName}']`).is(":checked") && !radioNameArrayStack.includes(radioName)) {
                        radioNameArrayStack.push(radioName);
                        processField($(`input[name='${radioName}']:checked`), variableIndex, data, onlyValueArray, formulaString);
                    }
                } else if (itemType === "checkbox" && $item.is(":checked")) {
                    processField($item, variableIndex, data, onlyValueArray, formulaString);
                } else if (itemType === "number" || itemType === "text" || itemType === "hidden" || itemType === "select-one") {
                    processField($item, variableIndex, data, onlyValueArray, formulaString);
                }
            });

            return [data, onlyValueArray];
        }

        /**
         * Process individual field and update data arrays
         */
        function processField($item, variableIndex, data, onlyValueArray, formulaString) {
            const realValue = getValueIfNumber($item.val());
            if (realValue !== null) {
                onlyValueArray.push({
                    variable: `f${variableIndex}`,
                    value: realValue,
                });
            }

            data.push({
                type: $item.prop("type"),
                index: variableIndex - 1,
                value: $item.val(),
                variable: `f${variableIndex}`,
                real_value: realValue,
            });

            formulaString += realValue < 0 ? `-f${variableIndex}, ` : `f${variableIndex}, `;
        }

        /**
         * Validate and parse number values
         */
        function getValueIfNumber(value) {
            const parsedValue = parseFloat(value);
            return !isNaN(parsedValue) ? parsedValue : null;
        }

        /**
         * Extract formula string from settings
         */
        function getFormulaStringFromDataSettings() {
            const formula = $settings.formula;
            const match = formula ? formula.match(/'(.*)'/) : null;
            return match ? match[1] : null;
        }

        /**
         * Process form data with formula.js
         */
        function processFormDataWithFormulaJs() {
            const [data, variableArray] = getVariableDataArray();
            const formulaString = getFormulaStringFromDataSettings();
            if (!formulaString || variableArray.length === 0) {
                return;
            }

            let processedFormula = formulaString;
            const regexp = /f[1-9][0-9]{0,2}|1000$/g;
            let match;

            while ((match = regexp.exec(formulaString)) !== null) {
                const variable = match[0];
                const variableData = variableArray.find(v => v.variable === variable);
                processedFormula = processedFormula.replace(variable, variableData ? variableData.value : null);
            }

            try {
                const result = eval(`formulajs.${processedFormula}`);
                $($settings.id).find(".penci-advcal-result span").text(result.toFixed(2));
            } catch (error) {
                showError();
            }
        }

        /**
         * Show error message
         */
        function showError() {
            const $errorElement = $($settings.id).find('.penci-advcal-el-ep-advanced-calculator-error');
            $errorElement.removeClass('penci-advcal-el-hidden');
            setTimeout(() => {
                $errorElement.addClass('penci-advcal-el-hidden');
            }, 5000);
        }

        // Bind events based on settings
        if ($settings.resultShow === 'submit') {
            $($settings.id).find(".penci-advcal-form").on("submit", function (e) {
                e.preventDefault();
                processFormDataWithFormulaJs();
            });
        } else if ($settings.resultShow === 'change') {
            $($settings.id).find(".penci-advcal-form input").on("change", processFormDataWithFormulaJs);
        }
    };

    // Initialize the widget
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/penci-advanced-calculator.default', widgetCCalculator);
    });

}(jQuery, window.elementorFrontend));