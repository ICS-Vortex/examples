import React from 'react';
import {Spinner} from "react-bootstrap";

const LoadingComponent = () => {
    return (
        <div className="text-center">
            <Spinner animation="border" variant="warning"/>
        </div>
    );
};

export default LoadingComponent;