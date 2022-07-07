import React, {useEffect} from 'react';
import {authFetch, useAuth} from "../../../providers/authProvider";
import i18n from "../../../i18n";
import {Popup} from 'devextreme-react/popup';
import {useTypedSelector} from "../../../hooks/useTypedSelector";
import SelectBox from 'devextreme-react/select-box';
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import {Button, Col, Row, Spinner} from "react-bootstrap";
import history from "../../../history";
import {PAGE_PROFILE} from "../../../constants/routes";
import {toast} from "react-toastify";
import ReactHtmlParser from "react-html-parser";
import SweetAlert from "react-bootstrap-sweetalert";

const CouponsModalComponent = () => {
    const [successMessage, setSuccessMessage] = React.useState('');
    const [show, setShow] = React.useState(false);
    const [isSuccess, setIsSuccess] = React.useState(false);
    const [loading, setLoading] = React.useState(false);
    const [_disabled, setDisabled] = React.useState(false);
    const [showAlert, setShowAlert] = React.useState(false);
    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);
    const [regions, setRegions] = React.useState([]);
    const [region, setRegion] = React.useState();
    const [coupon, setCoupon] = React.useState({});
    const [isLogged] = useAuth();
    const {user} = useTypedSelector(state => state.user);

    const redirectToProfile = () => {
        history.push(PAGE_PROFILE);
    };
    const handleRegion = (e) => {
        const r = regions.find(r => r.id === e.value);
        setRegion(r);
    };

    const acceptTicket = () => {
        setDisabled(true);
        setLoading(true);
        if (!region) {
            toast.error(i18n.t('placeholder.choose_region'), {
                autoClose: 5000,
                hideProgressBar: false,
                closeOnClick: true,
            });
            return;
        }
        const url = process.env.REACT_APP_API_HOST + `/api/${i18n.language}/coupons/${coupon.id}/accept?region=${region?.id}`;
        authFetch(url, {
            method: 'POST'
        })
            .then(r => r.json())
            .then(response => {
                if (response.status === 0) {
                    setSuccessMessage(response.message);
                    setIsSuccess(true);
                    setShowAlert(true);
                    handleClose();
                    toast.success(response.message, {
                        autoClose: 5000,
                        hideProgressBar: false,
                        closeOnClick: true,
                    });
                } else {
                    toast.error(response.message, {
                        autoClose: 5000,
                        hideProgressBar: false,
                        closeOnClick: true,
                    });
                }
            })
            .catch(e => {
                setSuccessMessage(e.message);
                setIsSuccess(false);
                setShowAlert(true);
            })
            .finally(() => {
                setDisabled(false);
                setLoading(false);
            })
        ;
        return;
    };

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/regions/list`)
            .then(r => r.json())
            .then(_regions => setRegions(_regions));
    }, []);

    useEffect(() => {
        if (user) {
            if (user.region) {
                setRegion(user.region);
            }
            if (user.tournamentCouponRequests) {
                setCoupon(user.tournamentCouponRequests[0]);
                handleShow();
            }
        }
    }, [user]);

    return <>
        {isLogged && coupon && coupon.active && <React.Fragment>
            <SweetAlert
                customClass={'text-black'}
                success={isSuccess}
                danger={!isSuccess}
                title={successMessage}
                onConfirm={() => setShowAlert(false)}
                onCancel={() => setShowAlert(false)}
                show={showAlert}
            />
            <Popup title={i18n.t('label.coupon_request')}
                   visible={show}
                   height="100%"
                   id={'#coupons-modal'}
                   width="80%"
                   onHiding={handleClose}
                   closeOnOutsideClick={true}>
                {!user.region && <React.Fragment>
                    <Row>
                        <Col md={{span: 6, offset: 3}}>
                            {<React.Fragment>
                                {i18n.t('message.coupon_choose_shop')}:
                            </React.Fragment>}
                        </Col>
                    </Row>
                    <Row>
                        <Col md={{span: 6, offset: 3}}>
                            <div className="dx-fieldset">
                                <div className="dx-field">
                                    <div className="dx-field-label">{i18n.t('label.region')}</div>
                                    <div className="dx-field-value">
                                        <SelectBox
                                            onValueChanged={handleRegion}
                                            dataSource={regions}
                                            displayExpr={i18n.language === LANGUAGE_ENGLISH ? 'titleEn' : 'title'}
                                            valueExpr="id"
                                            placeholder={i18n.t('placeholder.choose_region')}
                                            showClearButton={true}/>
                                    </div>
                                </div>
                            </div>
                        </Col>
                    </Row>
                </React.Fragment>}
                <Row>
                    <Col md={{span: 6, offset: 3}}>
                        <h5 className="text-danger">{i18n.t('label.region')}: {i18n.language === LANGUAGE_ENGLISH ? region?.titleEn : region?.title}</h5>
                    </Col>
                </Row>
                <Row>
                    <Col md={{span: 6, offset: 3}}>
                        {ReactHtmlParser((i18n.language === LANGUAGE_ENGLISH ? region?.couponDescriptionEn : region?.couponDescription))}
                    </Col>
                </Row>
                <Row>
                    <Col md={{span: 6, offset: 3}}>
                        <Button disabled={_disabled} className="m-4" variant="success" size="lg" active onClick={acceptTicket}>
                            {i18n.t('button.receive_coupon')}
                        </Button>
                        <Button variant="secondary" size="lg" active onClick={handleClose}>
                            {i18n.t('button.close')}
                        </Button>
                        <Button className="m-4" variant="primary" size="lg" active onClick={redirectToProfile}>
                            {i18n.t('button.edit_profile')}
                        </Button>
                    </Col>
                </Row>
                <Row>
                    <Col className="text-center">
                        <Spinner hidden={!loading} animation="border" variant="warning" />
                    </Col>
                </Row>
            </Popup>
        </React.Fragment>}
    </>;
};

export default CouponsModalComponent;