import React from 'react';
import {Button, Form} from "react-bootstrap";
import i18n from "../../../i18n";
import {toast} from "react-toastify";
import {URL_API_LOGIN_SOCIAL} from "../../../constants/urls";
import {login} from "../../../providers/authProvider";
import history from "../../../history";
import {PAGE_HOME} from "../../../constants/routes";

const UcidLoginForm = () => {
    const [ucid, setUcid] = React.useState(''); // TODO remove default
    const [loading, setLoading] = React.useState(false);

    const handleUcidChange = (e) => {
        setUcid(e.target.value);
    }

    const loginViaDcsUcid = async () => {
        if (!ucid) {
            toast.error(i18n.t('message.empty_ucid_field'), {
                position: "top-right",
                autoClose: 5000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                progress: undefined,
            });
            return;
        }

        const request = await fetch(URL_API_LOGIN_SOCIAL + `/${i18n.language}`, {
            method: 'POST',
            body: JSON.stringify({ucid: ucid.trim()})
        });
        const response = await request.json();

        if (response.status === 0) {
            const {token, refreshToken} = response;
            login({token, refreshToken});
            history.push(PAGE_HOME);
        } else {
            toast.error(i18n.t(response.message), {
                position: "top-right",
                autoClose: 5000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                progress: undefined,
            });
        }
    };
    return (
        <Form>
            <Form.Group controlId="ucid">
                <Form.Label>{i18n.t('label.ucid')}</Form.Label>
                <Form.Control disabled={loading}
                              value={ucid} onChange={handleUcidChange}
                              type="password" placeholder="Enter ucid" required/>
            </Form.Group>
            <div className="d-grid gap-2 mt-4">
                <Button size="lg" variant="outline-warning" type="button"
                        disabled={loading} onClick={loginViaDcsUcid}>
                    {i18n.t('button.signin')}
                </Button>
            </div>
        </Form>
    );
};

export default UcidLoginForm;