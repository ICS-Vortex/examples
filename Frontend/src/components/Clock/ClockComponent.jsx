import React from 'react';
import './ClockComponent.css';

const ClockComponent = (props) => {
    return (
        <React.Fragment>
            <div id="clock" className="row">
                <div className="timer">
                    <div className="middle" />
                </div>
            </div>
        </React.Fragment>
    );
}

export default ClockComponent;