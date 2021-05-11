/**
 * Provides a form that allows choosing multiple type for tasks.
 */
class taskTypePicker {

    /** Root form element */
    $form = null;

    /** Task type selector */
    $type = null;

    /**
     * Creates a new status picker form.
     *
     * @param selector      Root form selector
     */
    constructor(selector)
    {
        this.$form = $(selector);
        this.$type = this.$form.find('.task-type-select');
        this._initialize();
    }

    /**
     * Initializes the form widgets.
     */
    _initialize()
    {
        this.$type.select2({
            width: '100%',
            allowClear: true,
            dropdownCssClass: "fontSelect2",
        });
    }
}