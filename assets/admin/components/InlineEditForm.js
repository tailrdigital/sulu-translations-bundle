import React, {useState} from "react";
import {updateTranslationValue} from "../api/updateTranslationValue";

function InlineEditForm({translationId, value}) {
    const [saving, setSaving] = useState(false);
    const [editingValue, setEditingValue] = useState(value);
    const onChange = (event) => setEditingValue(event.target.value);
    const onKeyDown = (event) => {
        // if (event.key === "Enter" || event.key === "Escape") {
        //     event.target.blur();
        // }
    }

    const onBlur = (event) => {
        if ('' === event.target.value.trim()) {
            setEditingValue(value);
            return;
        }
        setSaving(true);
        updateTranslationValue(translationId, event.target.value)
            .then(() => console.log('saved!'))
            .catch((reason) => console.error('errored!', reason))
            .finally(() => setSaving(false));
    }

    return (
        <>
            <textarea
                style={{backgroundColor: saving ? 'yellow': 'white'}}
                value={editingValue}
                onChange={onChange}
                onKeyDown={onKeyDown}
                onBlur={onBlur}>
                {editingValue}
            </textarea>
        </>
    );
}

export default InlineEditForm;
