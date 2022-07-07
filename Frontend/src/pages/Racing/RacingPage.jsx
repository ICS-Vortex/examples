import React, {useEffect} from 'react';
import RacingDataComponent from "../../components/Racing/RacingDataComponent";
import {Col, Row} from "react-bootstrap";
import i18n from "../../i18n";
import PilotsRacingDataComponent from "../../components/Racing/PilotsRacingDataComponent";
import PilotsRacingRankingComponent from "../../components/Racing/PilotsRacingRankingComponent";

const RacingPage = () => {
    const [helicopters, setHelicopters] = React.useState([]);

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/planes/helicopters`)
            .then(r => r.json())
            .then(response => {
                setHelicopters(response);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    return <React.Fragment>
        <main className="main">
            <div className="content">
                <div className="main__content">
                    <Row className="pb-5">
                        <Col className="text-center">
                            <h1>{i18n.t('label.minvody_results')}</h1>
                        </Col>
                    </Row>
                    <Row>
                        {helicopters.map((aircraft) => (
                            <Col md={4} key={aircraft.id}>
                                <RacingDataComponent plane={aircraft}/>
                            </Col>
                        ))}
                    </Row>
                    <Row>
                        <Col md={6}>
                            <PilotsRacingDataComponent/>
                        </Col>
                        <Col md={6}>
                            <PilotsRacingRankingComponent title={i18n.t('label.personal_best_results')}/>
                        </Col>
                    </Row>
                </div>
            </div>
        </main>
    </React.Fragment>;
};

export default RacingPage;
