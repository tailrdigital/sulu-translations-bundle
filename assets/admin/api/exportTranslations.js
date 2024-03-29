import {Requester} from "sulu-admin-bundle/services";
import {runActionOnServer} from "../utilities/run-action-on-server";
import symfonyRouting from "fos-jsrouting/router";

/**
 * @returns {Promise<Object>}
 */
export async function exportTranslations(){
    return await runActionOnServer(
        Requester.post(symfonyRouting.generate('tailr.translations_export'))
    );
}