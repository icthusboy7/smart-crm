/**
 * Provides a wrapper for a form that can be serialized.
 */
class SerializableForm {

    /** Root form element */
    $form = null;


    /**
     * Creates a new authors picker form.
     *
     * @param selector      Root form selector
     */
    constructor(selector) {
        this.$form = $(selector);
    }


    /**
     * Registers a handler on the underlying form.
     *
     * @param event         Event name
     * @param handler       Handler function
     */
    on(event, handler) {
        this.$form.change(event, handler);
    }


    /**
     * Serializes the form as an object of key-value pairs.
     *
     * @return              Serialized form
     */
    serializeObject() {
        const fields = this.$form.serializeArray();
        const result = {};

        fields.forEach(field => {
            const key = field.name;
            const value = field.value;

            if (key in result === false) {
                result[key] = value;
                return;
            }

            if (!$.isArray(result[key])) {
                result[key] = [result[key]];
            }

            result[key].push(value);
        });

        return result;
    }
}
