import React, {Suspense} from 'react';
import './ProfilePage.module.css';
import {Col, Row} from "react-bootstrap";

import {useAuth} from "../../providers/authProvider";
import {useTypedSelector} from "../../hooks/useTypedSelector";
import LoadingComponent from "../../components/Loading/LoadingComponent";

const ProfileForm = React.lazy(() => import("../../components/Form/Profile/ProfileForm"));

const ProfilePage = () => {
    const [isLogged] = useAuth();
    const {user} = useTypedSelector(state => state.user);

    return (
        <main className="main">
            <div className="content">
                {isLogged && user?.id && <div className="main__content">
                    <Row>
                        <Col md={12} className="text-center">
                            <h1>{user?.username}</h1>
                        </Col>
                        <Col>
                            <Suspense fallback={<LoadingComponent/>}>
                                <ProfileForm/>
                            </Suspense>
                        </Col>
                    </Row>
                </div>}
            </div>
        </main>
    );
}

export default ProfilePage;