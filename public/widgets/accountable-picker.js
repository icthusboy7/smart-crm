/**
 * Provides a form that allows choosing multiple users.
 *
 * It is expected for the root form to contain an .authors-select and
 * a .managers-select select boxes that will be decorated using the
 * Select2 library. The managers box is used to select multiple users.
 */
class AccountablePicker {

    /** Authors service path */
    AUTHORS_PATH = '/query/findResponsableComercial';

    /** Managers service path */
    MANAGERS_PATH = '/tasks/selectResponsible';

    /** Root form element */
    $form = null;

    /** Authors multiselect element */
    $authors = null;

    /** Managers select element */
    $managers = null;


    /**
     * Creates a new authors picker form.
     *
     * @param selector      Root form selector
     */
    constructor(selector) {
        this.$form = $(selector);
        this.$authors = this.$form.find('.authors-select');
        this.$managers = this.$form.find('.managers-select');
        this._initialize();
        this._attachEvents();
    }


    /**
     * Initializes the form widgets.
     */
    _initialize() {
        this.$authors.select2({
            allowClear: true,
            dropdownCssClass: "fontSelect2",
            minimumInputLength: 1,
            placeholder: '',
            width: "100%",
            ajax: {
                cache: true,
                delay: 250,
                datatype: 'json',
                url: this.MANAGERS_PATH,
                data: r => ({ q: r.term }),
                processResults: r => ({ results: r })
            }
        });
    }


    /**
     * Attaches the requiered events to the form.
     */
    _attachEvents() {
        this.$managers.on('change', event => {
            this._onManagerChange(event);
        });

        this.$authors.on('select2:unselect', event => {
            this._onAuthorUnselect(event);
        });

        this.$authors.on('select2:clearing', event => {
            this._onAuthorClearing(event);
        });
    }


    /**
     * Fired after an author was unselected.
     *
     * This method clears the managers select box to allow any previously
     * selected manager to be choosen again.
     *
     * @param event         Browser event
     */
    _onAuthorUnselect(event) {
        this.$managers.prop('selectedIndex', 0);
    }


    /**
     * Fired before the current author selection is cleared.
     *
     * This method prevents Select2 from firing multiple change events
     * when the author's multiselect box is cleared.
     *
     * @param event         Browser event
     */
    _onAuthorClearing(event) {
        this._onAuthorUnselect();
        this.$authors.empty();
        this.$authors.trigger('select2:select', { data: [] });
        this.$authors.trigger('change');
        event.preventDefault();
    }


    /**
     * Fired after the selected manager changes.
     *
     * This method fetches the authors for the manager from the server
     * and appends the results to the authors multiselect box.
     *
     * @param event         Browser event
     */
    _onManagerChange(event) {
        const manager_id = event.target.value;

        this.fetchAuthors(manager_id);
        event.preventDefault();
        event.stopPropagation();
    }


    /**
     * Selects a previously unselected author. Notice that this method
     * does not fire any events; use {@see #_selectAuthors} for that.
     *
     * @param author        Author object
     */
    _selectAuthor(author) {
        const selector = `option[value='${author.id}']`;
        const $options = this.$authors.find(selector);

        if ($options.length) {
            $options.prop('selected', true);
        } else {
            const option = new Option(author.name, author.id, false, true);
            this.$authors.append(option);
        }
    }


    /**
     * Selects one ore more previously unselected authors.
     *
     * @param authors       An array of author objects
     */
    _selectAuthors(authors) {
        authors.forEach(author => this._selectAuthor(author));
        this.$authors.trigger('select2:select', { data: authors });
        this.$authors.trigger('change');
    }


    /**
     * Fetches the authors for wich a manager is responsible of from
     * the server and selects them.
     *
     * @param manager_id    A user identifier
     */
    fetchAuthors(manager_id) {
        let request = { responsable: manager_id };

        $.post(this.AUTHORS_PATH, request, result => {
            const authors = JSON.parse(result);
            this._selectAuthors(authors);
        });
    }
}
