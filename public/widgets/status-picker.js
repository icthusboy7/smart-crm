/**
 * Provides a form to pick multiple visit statuses.
 *
 * It is expected for the root form to contain checkboxes with the
 * name 'status_id' for each status.
 */
class StatusPicker {

    /** Root form element */
    $form = null;

    /** Input check boxes */
    $inputs = null;


    /**
     * Creates a new status picker form.
     *
     * @param selector      Root form selector
     * @param values        Initial values array
     */
    constructor(selector, values) {
        this.$form = $(selector);
        this.$inputs = this.$form.find('input[name="status_id"]');
    }


    /**
     * Sets the selected values of the form.
     *
     * @param values        Values array
     */
    setValues(values) {
        this.$inputs.each(function() {
            const $input = $(this);
            const value = parseInt($input.val());
            const checked = values.includes(value);
            $input.prop('checked', checked);
        });
    }
}
