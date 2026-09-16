import Modal from "./Modal.tsx";
import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";

function DuelGameFinishedModal({
                                   isModalOpen,
                                   me,
                                   otherPlayer
                               }) {
    const { t } = useTranslation();
    const navigate = useNavigate();

    const getGrandTotal = (player) => {
        return Object.values(player?.grandTotals ?? {}).reduce(
            (sum, value) => sum + (Number(value) || 0),
            0
        );
    };

    const myTotal = getGrandTotal(me);
    const otherPlayerTotal = getGrandTotal(otherPlayer);

    const isMyWin = myTotal > otherPlayerTotal;
    const isOtherPlayerWin = otherPlayerTotal > myTotal;
    const isDraw = myTotal === otherPlayerTotal;

    return (
        <Modal
            isOpen={isModalOpen}
            title={t("game.gameIsFinished")}
            withExit={false}
            onClose={null}
        >
            <div
                style={{
                    textAlign: "center",
                    marginBottom: "20px"
                }}
            >
                <div
                    style={{
                        padding: "16px",
                        marginBottom: "10px",
                        borderRadius: "12px",
                        background: isMyWin
                            ? "rgba(255, 193, 7, 0.12)"
                            : "rgba(0, 0, 0, 0.03)",
                        border: isMyWin
                            ? "2px solid rgba(255, 193, 7, 0.5)"
                            : "1px solid rgba(0, 0, 0, 0.08)"
                    }}
                >
                    <div
                        style={{
                            fontSize: "17px",
                            fontWeight: "600",
                            marginBottom: "5px"
                        }}
                    >
                        {me?.username}
                    </div>

                    <div
                        style={{
                            fontSize: "13px",
                            fontWeight: "700",
                            letterSpacing: "1px",
                            marginBottom: "5px",
                            opacity: isMyWin ? 1 : 0.5
                        }}
                    >
                        {isDraw ? t("game.draw") : isMyWin ? t("game.winner") : t("game.loser")}
                    </div>

                    <div
                        style={{
                            fontSize: "36px",
                            fontWeight: "800",
                            lineHeight: "1"
                        }}
                    >
                        {myTotal}
                    </div>
                </div>

                <div
                    style={{
                        fontSize: "13px",
                        fontWeight: "700",
                        opacity: 0.4,
                        margin: "8px 0"
                    }}
                >
                    VS
                </div>

                <div
                    style={{
                        padding: "16px",
                        borderRadius: "12px",
                        background: isOtherPlayerWin
                            ? "rgba(255, 193, 7, 0.12)"
                            : "rgba(0, 0, 0, 0.03)",
                        border: isOtherPlayerWin
                            ? "2px solid rgba(255, 193, 7, 0.5)"
                            : "1px solid rgba(0, 0, 0, 0.08)"
                    }}
                >
                    <div
                        style={{
                            fontSize: "17px",
                            fontWeight: "600",
                            marginBottom: "5px"
                        }}
                    >
                        {otherPlayer?.username}
                    </div>

                    <div
                        style={{
                            fontSize: "13px",
                            fontWeight: "700",
                            letterSpacing: "1px",
                            marginBottom: "5px",
                            opacity: isOtherPlayerWin ? 1 : 0.5
                        }}
                    >
                        {isDraw
                            ? t("game.draw")
                            : isOtherPlayerWin
                                ? t("game.winner")
                                : t("game.loser")}
                    </div>

                    <div
                        style={{
                            fontSize: "36px",
                            fontWeight: "800",
                            lineHeight: "1"
                        }}
                    >
                        {otherPlayerTotal}
                    </div>
                </div>
            </div>

            <button
                style={{ marginBottom: "10px" }}
                className="btn btn-primary fullSize"
                onClick={() => navigate("/")}
            >
                {t("dashboard.dashboard")}
            </button>

            <button
                className="btn fullSize"
                onClick={() => navigate("/leaderboard")}
            >
                {t("dashboard.leaderboard")}
            </button>
        </Modal>
    );
}

export default DuelGameFinishedModal;