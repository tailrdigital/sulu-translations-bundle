import React, {useState} from "react";
import {updateTranslationValue} from "../api/updateTranslationValue";
import {translate} from "sulu-admin-bundle/utils";
import snackbarStore from "sulu-admin-bundle/stores/snackbarStore";

/**
 *
 * @param {Object} props
 * @param {Number} props.translationId
 * @param {String} props.value
 * @returns {React.Element}
 * @constructor
 */
function InlineEditForm({translationId, value}) {
    const [saving, setSaving] = useState(false);
    const [editingValue, setEditingValue] = useState(value);
    const onChange = (event) => setEditingValue(event.target.value);

    const onError = () => {
        snackbarStore.add(
            {type: 'error', text: translate('tailr_translations.update_general_error_message')},
            8000
        );
        setEditingValue(value);
    }

    const onBlur = (event) => {
        if (value === event.target.value) {
            setEditingValue(value);
            return;
        }
        setSaving(true);
        updateTranslationValue(translationId, event.target.value)
            .catch(onError)
            .finally(() => setSaving(false));
    }

    return (
        <>
            <textarea
                key={`tailr_translations.inline_edit_form_${translationId}`}
                rows={2}
                cols={45}
                disabled={saving}
                value={editingValue}
                onChange={onChange}
                onBlur={onBlur}>
                {editingValue}
            </textarea>
        </>
    );
}

export default InlineEditForm;
