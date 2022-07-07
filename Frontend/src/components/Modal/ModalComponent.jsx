import React from 'react';
import './ModalComponent.module.css';

const ModalComponent = (props) => {

    const [open, setOpen] = React.useState(props.open);

    const getModalStyle = () => {
        return {
        };
    }

    const handleClose = () => {
        setOpen(false);
    };

    const styles = getModalStyle();
    const modalStyle = {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
    };
    return (
        <div style={modalStyle}
               aria-labelledby="simple-modal-title" aria-describedby="simple-modal-description">
            <div style={styles}>
                {props.children}
            </div>
        </div>

    );
}

export default ModalComponent;