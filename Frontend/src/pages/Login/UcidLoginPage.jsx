import React, {useEffect} from 'react';
import {Alert, Button, Col, Container, Form, Row} from "react-bootstrap";
import i18next from "../../i18n";
import i18n from "../../i18n";
import {toast} from "react-toastify";
import {useCookies} from 'react-cookie';
import history from "../../history";
import {PAGE_PROFILE} from "../../constants/routes";
import moment from "moment";

const UcidLoginPage = () => {
    const [loading] = React.useState(false);
    const [ucid, setUcid] = React.useState('');
    const [error] = React.useState('');
    const [showError, setShowError] = React.useState(false);
    const [cookies, setCookie] = useCookies(['ucid_token', 'ucid_token_expires']);

    const handleKeyPress = (e) => {
        if (e.key === 'Enter') {
        }
    }
    const handleUcidChange = (e) => {
        setUcid(e.target.value);
    }

    const handleLogin = async () => {
        const url = process.env.REACT_APP_API_HOST + `/api/open/ucid/${i18n.language}/login`;
        const data = new FormData();
        data.append("ucid", ucid);
        fetch(url, {
            method: 'POST',
            body: data
        })
            .then(r => r.json())
            .then(response => {
                if (response.token) {
                    setCookie('ucid_token', response.token, {path: '/'});
                    setCookie('ucid_token_expires', response.expires, {path: '/'});
                    history.push(PAGE_PROFILE);
                } else {
                    toast.error(response.message, {
                        position: "top-right",
                        autoClose: 5000,
                        closeOnClick: true,
                    });
                }
            })
            .catch(e => {
                toast.error(e.message, {
                    position: "top-right",
                    autoClose: 5000,
                    closeOnClick: true,
                });
            })
        ;
    }
    //
    // const responseFacebook = (response) => {
    //     const facebookId = response.id;
    //     loginViaFacebook(facebookId);
    // }

    // const loginViaFacebook = (facebookId?) => {
    //     if (!facebookId) {
    //         return;
    //     }
    //
    //     fetch(URL_API_LOGIN_SOCIAL, {
    //         method: 'POST',
    //         body: JSON.stringify({facebookId})
    //     })
    //         .then(r => r.json())
    //         .then(response => {
    //             const {status, message, data} = response;
    //             if (status === 0) {
    //                 // loginPilot(data.token, data.refreshToken);
    //             } else {
    //                 toast.error(i18next.t(message), {
    //                     position: "top-right",
    //                     autoClose: 5000,
    //                     hideProgressBar: false,
    //                     closeOnClick: true,
    //                     pauseOnHover: true,
    //                     draggable: true,
    //                     progress: undefined,
    //                 });
    //             }
    //         })
    //     ;
    // };

    useEffect(() => {
        // console.log(cookies.ucid_token_expires);
        if (moment().unix() < cookies.ucid_token_expires) {
            // console.log('redirecting to PAGE_PROFILE')
            history.push(PAGE_PROFILE);
        } else {
            // console.log('Token expired, login required')
        }
    }, []);


    return (
        <main className="main main_gradient text-white">
            <div className="content">
                <div className="main__content">
                    <Container>
                        <Row>
                            <Col/>
                            <Col xs={12} sm={6} md={4} lg={4}>
                                <Form>
                                    <Form.Group controlId="formBasicEmail">
                                        <Form.Label>{i18next.t('label.ucid')}</Form.Label>
                                        <Form.Control disabled={loading} onKeyPress={handleKeyPress}
                                                      value={ucid} onChange={handleUcidChange}
                                                      type="email" placeholder="UCID" required={true}/>
                                    </Form.Group>
                                    <div className="d-grid gap-2 mt-4">
                                        <Button size="lg" variant="outline-warning" type="button"
                                                disabled={loading} onClick={handleLogin}>
                                            {i18next.t('button.signin')}
                                        </Button>
                                    </div>
                                    {/*<div className="d-grid gap-2">*/}
                                    {/*    <FacebookLogin*/}
                                    {/*        appId={process.env.REACT_APP_FACEBOOK_APP_ID}*/}
                                    {/*        fields="name,email,picture"*/}
                                    {/*        callback={responseFacebook}*/}
                                    {/*        cssClass="btn btn-primary btn-block mt-3"*/}
                                    {/*        icon="fa-facebook"*/}
                                    {/*    />*/}
                                    {/*</div>*/}
                                    {showError &&
                                    <Alert className="mt-2" variant="danger" onClose={() => setShowError(false)}
                                           dismissible>
                                        <Alert.Heading>{i18next.t('label.error')}</Alert.Heading>
                                        <p>{error}</p>
                                    </Alert>}
                                </Form>
                            </Col>
                            <Col/>
                        </Row>
                    </Container>
                </div>
            </div>
        </main>
    );
}

export default UcidLoginPage;