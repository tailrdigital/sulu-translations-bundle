import {listToolbarActionRegistry} from 'sulu-admin-bundle/views';
import {listFieldTransformerRegistry} from 'sulu-admin-bundle/containers';
import {initializer} from 'sulu-admin-bundle/services';
import ExportTranslationsToolbarAction from "./components/ExportTranslationsToolbarAction";
import InlineEditFieldTransformer from "./components/InlineEditFieldTransformer";

initializer.addUpdateConfigHook('sulu_admin', (config, initialized) => {
    if (!initialized) {
        registerToolbarActions();
        registerFieldTransformers();
    }
});

function registerToolbarActions() {
    listToolbarActionRegistry.add('tailr_translation.export_translations', ExportTranslationsToolbarAction);
}

function registerFieldTransformers() {
    listFieldTransformerRegistry.add('tailr_translation.inline_edit', new InlineEditFieldTransformer());
}