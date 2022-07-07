import React, {Suspense, useEffect} from 'react';
import './LoginPage.module.css';
import {useAuth} from '../../providers/authProvider';
import history from "../../history";
import {PAGE_HOME} from "../../constants/routes";
import {Col, Container, Row, Tab, Tabs} from "react-bootstrap";
import LoadingComponent from "../../components/Loading/LoadingComponent";

const LoginForm = React.lazy(() => import('../../components/Form/Login/LoginForm'));
const UcidLoginForm = React.lazy(() => import('../../components/Form/Login/UcidLoginForm'));

const LoginPage = () => {
    const [isLogged] = useAuth();

    useEffect(() => {
        if (isLogged) {
            history.push(PAGE_HOME);
        }
    }, []);

    return (
        <main className="main main_gradient text-white">
            <div className="content">
                <div className="main__content">
                    <Tabs defaultActiveKey="profile" id="uncontrolled-tab-example" className="mb-3">
                        <Tab eventKey="home" title="Default login">
                            <Container>
                                <Row>
                                    <Col md={{span: 4, offset: 3}}>
                                        <Suspense fallback={<LoadingComponent/>}>
                                            <LoginForm/>
                                        </Suspense>
                                    </Col>
                                </Row>
                            </Container>
                        </Tab>
                        <Tab eventKey="profile" title="DCS World Login">
                            <Container>
                                <Row>
                                    <Col md={{span: 4, offset: 3}}>
                                        <Suspense fallback={<LoadingComponent/>}>
                                            <UcidLoginForm/>
                                        </Suspense>
                                    </Col>
                                </Row>
                            </Container>
                        </Tab>
                    </Tabs>
                </div>
            </div>
        </main>
    );
}

export default LoginPage;
