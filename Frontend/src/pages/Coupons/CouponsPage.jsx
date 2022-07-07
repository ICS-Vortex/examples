import React, {Suspense, useEffect} from 'react';
import {authFetch, useAuth} from "../../providers/authProvider";
import {useTypedSelector} from "../../hooks/useTypedSelector";
import i18n from "../../i18n";
import LoadingComponent from "../../components/Loading/LoadingComponent";
import {Col, Row} from "react-bootstrap";

const CouponComponent = React.lazy(() => import('../../components/Coupons/CouponComponent'));

const CouponsPage = () => {
    const [isLogged] = useAuth();
    const {user} = useTypedSelector(state => state.user);
    const [coupons, setCoupons] = React.useState([]);

    useEffect(() => {
        if (isLogged) {
            authFetch(process.env.REACT_APP_API_HOST + `/api/${i18n.language}/coupons/list`)
                .then(r => r.json())
                .then(_coupons => {
                    console.log(coupons);
                    setCoupons(_coupons);
                })
            ;
        }
    }, [isLogged]);

    return (
        <main className="main">
            <div className="content">
                <Suspense fallback={<LoadingComponent/>}>
                    {isLogged && user?.id && <div className="main__content">
                        <div className="title">Coupons</div>
                        <Row>
                            {coupons.map(coupon => (
                                <Col md={4} key={coupon.id} className="mb-4">
                                    <CouponComponent coupon={coupon}/>
                                </Col>
                            ))}
                        </Row>
                    </div>}
                </Suspense>
            </div>
        </main>
    );
};

export default CouponsPage;