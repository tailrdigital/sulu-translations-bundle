import {Requester} from "sulu-admin-bundle/services";
import {runActionOnServer} from "../utilities/run-action-on-server";

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
        Requester.put(`/admin/api/translations/${id}`, {
            translation: translation
        })
    );
}
