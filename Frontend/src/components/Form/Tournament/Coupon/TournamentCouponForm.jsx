import React, {useEffect} from 'react';
import {Col, Row, Spinner} from "react-bootstrap";
import i18n from "../../../../i18n";
import SelectBox from "devextreme-react/select-box";
import {LANGUAGE_ENGLISH} from "../../../../constants/languages";
import Validator, {EmailRule, RequiredRule} from "devextreme-react/validator";
import ReactHtmlParser from "react-html-parser";
import {TextBox} from "devextreme-react/text-box";
import {ValidationSummary} from "devextreme-react";
import Button from "devextreme-react/button";
import {toast} from "react-toastify";
import SweetAlert from "react-bootstrap-sweetalert";

const TournamentCouponForm = ({tournament}) => {
    const [successMessage, setSuccessMessage] = React.useState('');
    const [regions, setRegions] = React.useState([]);
    const [region, setRegion] = React.useState(0);
    const [regionHelp, setRegionHelp] = React.useState('');
    const [email, setEmail] = React.useState('');
    const [ucid, setUcid] = React.useState(localStorage.getItem('ucid') || '');
    const [loading, setLoading] = React.useState(false);
    const [_disabled, setDisabled] = React.useState(false);
    const [showAlert, setShowAlert] = React.useState(false);
    const [formClass, setFormClass] = React.useState('');
    const [isSuccess, setIsSuccess] = React.useState(false);

    const onFormSubmit = (e) => {
        e.preventDefault();
        setDisabled(true);
        setLoading(true);
        fetch(process.env.REACT_APP_API_HOST + `/api/open/coupons/receive?tournament=${tournament.id}&_locale=${i18n.language}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ucid, region, email})
        })
            .then(r => r.json())
            .then(response => {
                console.log(response);
                if (response.status === 0) {
                    setFormClass('d-block')
                    setSuccessMessage(response.message);
                    setIsSuccess(true);
                    setShowAlert(true);
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
    };
    const handleEmailChange = (e) => {
        setEmail(e.value);
    };
    const handleRegionChange = (e) => {
        setRegion(e.value);
        const _region = regions.find(r => r.id === e.value);
        if (_region) {
            setRegionHelp((i18n.language === LANGUAGE_ENGLISH ? _region.couponDescriptionEn : _region.couponDescription));
        }
    };
    const handleUcidChange = (e) => {
        setUcid(e.value);
    };

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/regions/list`)
            .then(r => r.json())
            .then(_regions => setRegions(_regions));
    }, []);


    return (
        <React.Fragment>
            <SweetAlert
                customClass={'text-black'}
                success={isSuccess}
                danger={!isSuccess}
                title={successMessage}
                onConfirm={() => setShowAlert(false)}
                onCancel={() => setShowAlert(false)}
                show={showAlert}
            />
            <Row>
                <Col md={{span: 4, offset: 3}}>
                    <form className={formClass} onSubmit={onFormSubmit}>
                        <div className="dx-fieldset">
                            <div className="dx-fieldset-header">{i18n.t('label.coupon_request')}</div>
                            <div className="dx-field">
                                <div className="dx-field-label">{i18n.t('label.region')}</div>
                                <div className="dx-field-value">
                                    <SelectBox dataSource={regions}
                                               placeholder={i18n.t('placeholder.choose_region')}
                                               onValueChanged={handleRegionChange}
                                               displayExpr={i18n.language === LANGUAGE_ENGLISH ? 'titleEn' : 'title'}
                                               valueExpr="id">
                                        <Validator>
                                            <RequiredRule message={i18n.t('validation.region_required')}/>
                                        </Validator>
                                    </SelectBox>
                                </div>
                                <div className="dx-field-item-help-text">
                                    {ReactHtmlParser(regionHelp)}
                                </div>
                            </div>
                            <div className="dx-field">
                                <div className="dx-field-label">UCID</div>
                                <div className="dx-field-value">
                                    <TextBox mode="password" value={ucid} valueChangeEvent="keyup"
                                             onValueChanged={handleUcidChange}
                                             placeholder={i18n.t('placeholder.type_ucid')}/>
                                </div>
                            </div>
                            <div className="dx-field">
                                <div className="dx-field-label">{i18n.t('label.email')}</div>
                                <div className="dx-field-value">
                                    <TextBox name="email" value={email} onValueChanged={handleEmailChange}
                                             valueChangeEvent="keyup"
                                             placeholder={i18n.t('placeholder.type_email')}>
                                        <Validator>
                                            <RequiredRule message={i18n.t('validation.email_required')}/>
                                            <EmailRule message={i18n.t('validation.email_invalid')}/>
                                        </Validator>
                                    </TextBox>
                                </div>
                            </div>
                            <div className="dx-fieldset m-5">
                                <ValidationSummary id="summary"/>
                                <Button
                                    disabled={_disabled}
                                    id="button"
                                    text={i18n.t('button.receive_coupon')}
                                    type="success"
                                    useSubmitBehavior={true}/>
                            </div>
                        </div>
                    </form>
                </Col>
            </Row>
            <Row>
                <Col className="text-center">
                    <Spinner hidden={!loading} animation="border" variant="warning"/>
                </Col>
            </Row>
        </React.Fragment>
    );
};

export default TournamentCouponForm;