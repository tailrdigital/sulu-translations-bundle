import React from 'react';
import InlineEditForm from "./InlineEditForm";

export default class InlineEditFieldTransformer {
    transform(value) {
        const [translationId, translationValue] = value.split(';', 2);

        return (
            <div>
                <InlineEditForm value={translationValue} translationId={translationId}/>
            </div>
        );
    }
}
