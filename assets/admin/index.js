import {listFieldTransformerRegistry} from 'sulu-admin-bundle/containers';
import {initializer} from 'sulu-admin-bundle/services';
import InlineEditFieldTransformer from "./components/InlineEditFieldTransformer";

initializer.addUpdateConfigHook('sulu_admin', (config, initialized) => {
    if (!initialized) {
        registerFieldTransformers();
    }
});

function registerFieldTransformers() {
    listFieldTransformerRegistry.add('tailr_translation.inline_edit', new InlineEditFieldTransformer());
}
