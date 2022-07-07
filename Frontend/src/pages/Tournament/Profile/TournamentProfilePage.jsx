import React, {useEffect} from 'react';
import {useAuth} from "../../../providers/authProvider";
import i18n from "../../../i18n";
import {LANGUAGE_ENGLISH} from "../../../constants/languages";
import {Col, ListGroup, ListGroupItem, Row} from "react-bootstrap";
import TournamentNavbarComponent from "../../../components/Tournament/Navbar/TournamentNavbarComponent";
import Image from "react-bootstrap/Image";
import {useTypedSelector} from "../../../hooks/useTypedSelector";

const TournamentProfilePage = (props) => {
    const [isLogged] = useAuth();
    const [tournament, setTournament] = React.useState({});
    const {user} = useTypedSelector(state => state.user);

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/${parseInt(props.match.params.id)}/info`)
            .then(r => r.json())
            .then(data => {
                setTournament(data);
            })
            .catch(e => {
                setTournament({});
            })
        ;
    }, []);

    return (
        <React.Fragment>
            <main className="main">
                <div className="content text-center text-uppercase">
                    <h1>{i18n.language === LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}</h1>
                </div>

                {tournament.id && isLogged && user.id && <div className="content">
                    <TournamentNavbarComponent tournament={tournament} active={'profile'}/>
                    <div className="main__content">
                        <Row>
                            <Col md={4}>
                                <div className="position-relative">
                                    <Image fluid src={process.env.REACT_APP_API_HOST + `/uploads/avatars/` + encodeURIComponent(user.photo)} rounded />
                                    <div  className="position-absolute" style={{top: '80%', right: '-15%'}}>
                                        <Image fluid width={100} src={process.env.REACT_APP_API_HOST + `/uploads/avatars/` + encodeURIComponent(user.photo)} roundedCircle />
                                    </div>
                                </div>
                            </Col>
                            <Col md={4}></Col>
                            <Col md={4}></Col>
                        </Row>
                        <Row className="mt-5">
                            <Col md={4}>
                                <div>{i18n.t('label.country')}</div>
                                <div>{i18n.t('label.coupons')}</div>
                                <div>{i18n.t('label.region')}</div>
                                <div>{i18n.t('label.instagram')}</div>
                                <div>{i18n.t('label.youtube')}</div>
                                <div>{i18n.t('label.facebook')}</div>
                                <div>{i18n.t('label.vk')}</div>
                                <div>{i18n.t('label.twitch')}</div>
                            </Col>
                            <Col md={4}></Col>
                            <Col md={4}></Col>
                        </Row>
                    </div>
                </div>}
            </main>
        </React.Fragment>
    );
};

export default TournamentProfilePage;