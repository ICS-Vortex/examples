import React from 'react';
import {Button, Form} from "react-bootstrap";
import i18n from "../../../i18n";
import FacebookLogin from "react-facebook-login";
import axios, {AxiosResponse} from "axios";
import {login} from "../../../providers/authProvider";
import history from "../../../history";
import {PAGE_HOME} from "../../../constants/routes";
import {toast} from "react-toastify";
import {URL_API_LOGIN_SOCIAL} from "../../../constants/urls";

const LoginForm = () => {
    const [username, setUsername] = React.useState('');
    const [password, setPassword] = React.useState('');
    const [loading, setLoading] = React.useState(false);

    const handleUsernameChange  = (e) => {
        setUsername(e.target.value);
    }

    const handleKeyPress = (e) => {
        if (e.key === 'Enter') {
            handleLogin();
        }
    }

    const handlePasswordChange = (e) => {
        setPassword(e.target.value);
    }

    const responseFacebook = (response) => {
        const facebookId = response.id;
        loginViaFacebook(facebookId);
    }

    const loginPilot = (accessToken, refreshToken) => {

    };

    const loginViaFacebook = (facebookId) => {
        if (!facebookId) {
            return;
        }

        fetch(URL_API_LOGIN_SOCIAL, {
            method: 'POST',
            body: JSON.stringify({facebookId})
        })
            .then(r => r.json())
            .then(response => {
                const {status, message, data} = response;
                if (status === 0) {
                    loginPilot(data.token, data.refreshToken);
                } else {
                    toast.error(i18n.t(message), {
                        position: "top-right",
                        autoClose: 5000,
                        hideProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true,
                        progress: undefined,
                    });
                }
            })
        ;
    };

    const handleLogin = () => {
        const url = process.env.REACT_APP_API_HOST + '/api/login_check';
        setLoading(true);
        const data = {
            username: username.trim(),
            password: password.trim()
        };
        axios.post(url, data, {headers: {'Content-Type': 'application/json'}})
            .then((response) => {
                const {token, refreshToken} = response.data;
                login({token, refreshToken});
                history.push(PAGE_HOME);
            })
            .catch(err => {
                const data = err.response.data;
                toast.error(data.message, {
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                });
            })
            .finally(() => {
                setLoading(false);
            })
        ;
    }

    return (
        <Form>
            <Form.Group controlId="default">
                <Form.Label>{i18n.t('label.email')}</Form.Label>
                <Form.Control disabled={loading} onKeyPress={handleKeyPress}
                              value={username} onChange={handleUsernameChange}
                              type="email" placeholder={i18n.t('label.email')} required/>
            </Form.Group>
            <Form.Group className="mt-3" controlId="formBasicPassword">
                <Form.Label>{i18n.t('label.password')}</Form.Label>
                <Form.Control type="password" placeholder={i18n.t('label.password')} required
                              disabled={loading} onKeyPress={handleKeyPress}
                              onChange={handlePasswordChange}
                              value={password}/>
            </Form.Group>
            <div className="d-grid gap-2 mt-4">
                <Button size="lg" variant="outline-warning" type="button"
                        disabled={loading} onClick={handleLogin}>
                    {i18n.t('button.signin')}
                </Button>
            </div>
            <div className="d-grid gap-2">
                <FacebookLogin
                    appId={process.env.REACT_APP_FACEBOOK_APP_ID}
                    fields="name,email,picture"
                    callback={responseFacebook}
                    cssClass="btn btn-primary btn-block mt-3"
                    icon="fa-facebook"
                />
            </div>
        </Form>
    );
};

export default LoginForm;