import React, {ReactNode} from "react";
import {observable, runInAction} from "mobx";
import {AbstractListToolbarAction} from "sulu-admin-bundle/views";
import {translate} from "sulu-admin-bundle/utils";
import ExportTranslationsDialog from "./ExportTranslationsDialog";

export default class ExportTranslationsToolbarAction extends AbstractListToolbarAction {

    /**
     * @type {boolean}
     */
    @observable showDialog = false;

    getToolbarItemConfig() {
        return {
            type: 'button',
            icon: 'su-upload',
            label: translate('Export translations'),
            onClick: this.handleOpen
        };
    }

    /**
     * @returns {ReactNode}
     */
    getNode(){
        if (!this.showDialog) {
            return null;
        }

        return <>
            <ExportTranslationsDialog
                key={'tailr_translation.export_translations'}
                onCancel={this.handleClose}
                onSuccess={this.handleClose}
            />
        </>
    }

    handleOpen = async () => {
        runInAction(() => {
            this.showDialog = true;
        })
    };

    handleClose = () => {
        runInAction(() => {
            this.showDialog = false;
        })
    };
}