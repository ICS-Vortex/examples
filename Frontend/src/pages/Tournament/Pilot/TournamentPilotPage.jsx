import React, {useEffect} from 'react';
import {Col, Row} from "react-bootstrap";
import Image from 'react-bootstrap/Image'
import i18n from "../../../i18n";
import {LANGUAGE_RUSSIAN} from "../../../constants/languages";

const TournamentPilotPage = (props) => {
    const [tournament, setTournament] = React.useState({});
    const stage = props.match.params.stage;
    const code = props.match.params.code;
    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/current`)
            .then(r => r.json())
            .then(data => {
                setTournament(data);
            })
            .catch(e => {
                // console.log(e)
            })
        ;
    }, []);

    useEffect(() => {

    }, [stage, code]);
    return (
        <React.Fragment>
            <main className="main">
                <div className="content">
                    <div className="">
                        <Row>
                            <Col md={3}>
                                <Image className="mt-5" src={`${process.env.REACT_APP_API_HOST}/images/pilot.jpg`}
                                       thumbnail/>
                            </Col>
                            <Col md={1}/>
                            <Col md={8}>
                                {tournament && <React.Fragment>
                                    <div className="text-center">
                                        <h1>{i18n.t('label.tournament')} {`"${i18n.language === LANGUAGE_RUSSIAN ? tournament.title : tournament.titleEn}"`}</h1>
                                    </div>
                                    <div className="p-4">
                                        <div
                                            className="parallelogram text-center fw-bold bg-white w-100 text-uppercase text-black fw-bold">
                                            <div className="text"><h1>Карточка пилота</h1></div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Бортовой</div>
                                            </div>
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">52</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram-inversed justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Никнейм</div>
                                            </div>
                                            <div
                                                className="parallelogram-inversed justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">ICS_Vortex</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-dark p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Имя</div>
                                            </div>
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Василий</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram-inversed justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Фамилия</div>
                                            </div>
                                            <div
                                                className="parallelogram-inversed justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Комаричин</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Возраст</div>
                                            </div>
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">32</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram-inversed justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Страна</div>
                                            </div>
                                            <div
                                                className="parallelogram-inversed justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Украина</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Лучшее личное время</div>
                                            </div>
                                            <div
                                                className="parallelogram justify-content-between d-flex bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">1:12.52</div>
                                                <div className="text ml-4 mr-4">+0:00.53</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram-inversed justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Лучшее за этап</div>
                                            </div>
                                            <div
                                                className="parallelogram-inversed justify-content-between d-flex bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">1:12.52</div>
                                                <div className="text ml-4 mr-4">+0:00.53</div>
                                            </div>
                                        </div>
                                        <div className="d-flex w-auto mt-4">
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">Летательный аппарат</div>
                                            </div>
                                            <div
                                                className="parallelogram justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                                <div className="text ml-4 pl-4">DCS: Mi-24P Hind</div>
                                            </div>
                                        </div>
                                    </div>
                                </React.Fragment>}
                            </Col>
                        </Row>
                    </div>
                </div>
            </main>
        </React.Fragment>
    );
};

export default TournamentPilotPage;