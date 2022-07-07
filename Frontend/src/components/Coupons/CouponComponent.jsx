import React from 'react';
import {Card} from "react-bootstrap";
import i18n from "../../i18n";
import {LANGUAGE_ENGLISH} from "../../constants/languages";

const CouponComponent = ({coupon}) => {
    return (
        <Card className="bg-dark text-white">
            <Card.Body>
                <Card.Title>{i18n.language === LANGUAGE_ENGLISH ? coupon?.tournament?.titleEn : coupon?.tournament?.title}</Card.Title>
                <Card.Subtitle
                    className="mb-2 text-muted">{i18n.t('label.deadline')}: {coupon?.couponDeadline}</Card.Subtitle>
                <Card.Text>
                    <b className="text-danger">{coupon?.code}</b>
                </Card.Text>
            </Card.Body>
        </Card>
    );
};

export default CouponComponent;