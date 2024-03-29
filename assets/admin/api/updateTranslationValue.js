import {Requester} from "sulu-admin-bundle/services";
import {runActionOnServer} from "../utilities/run-action-on-server";
import symfonyRouting from "fos-jsrouting/router";

/**
 * @param {number} id
 * @param {string} translation
 * @returns {Promise<void>}
 */
export async function updateTranslationValue(
    id,
    translation,
) {
    await runActionOnServer(
        Requester.put(symfonyRouting.generate('tailr.translations_update', {id}), {
            translation: translation
        })
    );
}