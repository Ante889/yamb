import Modal from "../../../components/Modal/Modal.tsx";

function CreateSoloGame({isModalOpen, setIsModalOpen}) {
    return (
        <>
            <Modal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                title="Create solo game"
            >
                <p>Ovdje ide sadržaj modala.</p>
            </Modal>
        </>
    );
}

export default CreateSoloGame;
