/**
 * Provides a form that allows choosing multiple status for tasks.
 */
class taskStatusPicker {

    /** Root form element */
    $form = null;

    /** Task status selector */
    $status = null;

    /**
     * Creates a new status picker form.
     *
     * @param selector      Root form selector
     */
    constructor(selector)
    {
        this.$form   = $(selector);
        this.$status = this.$form.find('.task-status-select');
        this._initialize();
    }

    /**
     * Initializes the form widgets.
     */
    _initialize()
    {
        this.$status.select2({
            width: '100%',
            allowClear: true,
            dropdownCssClass: "fontSelect2",
        });
    }
}