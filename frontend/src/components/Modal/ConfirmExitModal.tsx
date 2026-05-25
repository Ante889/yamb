import Modal from "./Modal.tsx";
import {useTranslation} from "react-i18next";

function ConfirmExitModal({ open, onClose, onConfirm }) {
    const { t } = useTranslation();
    return (
        <Modal isOpen={open} onClose={onClose} title={t('dashboard.exitConfirmation')}>
            <p>{t('game.confirmExit')}</p>

            <div style={{ display: "flex", justifyContent: "flex-end", gap: 10, marginTop: 15 }}>
                <button
                    className="btn btn-error"
                    onClick={onConfirm}
                >
                    {t('game.exit')}
                </button>
            </div>
        </Modal>
    );
}

export default ConfirmExitModal;
