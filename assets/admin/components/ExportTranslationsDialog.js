import React, {useState} from "react";
import {translate} from "sulu-admin-bundle/utils";
import {Dialog} from "sulu-admin-bundle/components";
import {exportTranslations} from "../api/exportTranslations";
import snackbarStore from "sulu-admin-bundle/stores/snackbarStore";

/**
 *
 * @param {Object} props
 * @param {function} props.onCancel
 * @param {function} props.onSuccess
 * @returns {JSX.Element}
 * @constructor
 */
function ExportTranslationsDialog(props) {
    const {onCancel} = props;
    const [loading, setLoading] = useState(false);

    /**
     *
     * @param {Object} response
     * @param {string} response.message
     */
    const onSuccess = (response) => {
        setLoading(false);
        snackbarStore.add(
            {type: 'success', text: response.message},
            8000
        );
        props.onSuccess();
    }

    const onError = () => {
        setLoading(false);
        snackbarStore.add(
            {type: 'error', text: translate(`Something went wrong during export. Please check the server logs.`)},
            8000
        );
        props.onCancel();
    }

    const doExport = () => {
        setLoading(true);
        exportTranslations()
            .then(onSuccess)
            .catch(onError)
    }

    return (
        <>
            <Dialog
                title={`${translate('Export translations')}`}
                open={true}
                snackbarType="warning"
                cancelText={translate('Cancel')}
                confirmText={translate('Ok')}
                onCancel={onCancel}
                onConfirm={doExport}
                confirmLoading={loading}
            >
                {translate('Are you sure you want to export all the translations?')}
            </Dialog>
        </>
    );
}

export default ExportTranslationsDialog;
