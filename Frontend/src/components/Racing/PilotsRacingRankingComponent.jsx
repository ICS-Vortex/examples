import React, {useEffect} from 'react';
import i18n from "../../i18n";
import moment from "moment";
import DataGrid, {Column, Pager, Paging, SearchPanel} from 'devextreme-react/data-grid';
import {Col, Modal, Row} from "react-bootstrap";
import {LANGUAGE_RUSSIAN} from "../../constants/languages";
import Image from "react-bootstrap/Image";
import {countries} from 'country-data';

const PilotsRacingRankingComponent = ({title, tournament, stage}) => {
    const pageSizes = [25, 50, 100];
    const [show, setShow] = React.useState(false);
    const [pilot, setPilot] = React.useState({});

    const handleClose = () => setShow(false);
    const handleShow = (pilot) => {
        if (!tournament && !stage) {
            return;
        }
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournament/stages/${stage?.id}/pilot/${pilot.id}`)
            .then(r => r.json())
            .then(data => {
                setPilot(data);
                setShow(true);
            })
            .finally(() => {
            })
    };

    const dataGridAttributes = {
        id: 'racing-ranking',
        class: 'p-2 text-white'
    };
    const [data, setData] = React.useState([]);

    const formatTime = (cell) => {
        return moment.utc(cell.value * 1000).format('mm:ss.SS');
    };
    const formatPilot = (cell) => {
        return <>
            <span title={cell.data.country?.toUpperCase()} className={`m-2 flag-icon flag-icon-${cell.data.country}`}/>
            <span className={`ml-2 pointer`}>
                <a href="#" onClick={(e) => (handleShow(cell.data))}>{cell.data.callsign}</a>
            </span>
        </>
    };

    const getDeltaTime = (type, pilot) => {
        if (typeof (pilot.bestEver) === 'undefined' && typeof (pilot.time) === 'undefined') {
            return;
        }
        let time = pilot.time;
        if (type === 'best') {
            time = pilot.best;
        }
        if (type === 'qualification') {
            time = pilot.bestQualification;
        }

        if (time === pilot.bestEver) {
            return <></>;
        }
        time = time ?? 0;
        if (time === 0) {
            return;
        }
        let delta = (pilot.bestEver ?? 0) - (time);
        if (delta < 0) {
            delta = delta * -1;
        }
        if (delta === 0) {
            return <></>;
        }
        let sign;
        if (delta > 0) {
            sign = '+';
        }

        if (delta < 0) {
            sign = '-';
        }
        return <span>{sign} {moment.utc(delta * 1000).format('mm:ss.SS')}</span>
    };

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/racing/ranking?tournament=${tournament?.id}&stage=${stage?.id}`)
            .then(r => r.json())
            .then(response => {
                setData(response);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, [tournament, stage, title]);

    return <React.Fragment>
        <>
            <Modal show={show} onHide={handleClose} dialogClassName="modal-100w" size="xl"
                   aria-labelledby="contained-modal-title-vcenter" centered>
                <Modal.Body className="bg-dark">
                    <Row className="p-4">
                        <Col md={3}>
                            <Image className="mt-5" src={`${process.env.REACT_APP_API_HOST}/images/pilot.jpg`}
                                   thumbnail/>
                        </Col>
                        <Col md={1}/>
                        <Col md={8}>
                            {tournament && pilot.id && <React.Fragment>
                                <div className="text-center">
                                    <h1>{i18n.t('label.tournament')} {`"${i18n.language === LANGUAGE_RUSSIAN ? tournament.title : tournament.titleEn}"`}</h1>
                                </div>
                                <div className="p-4">
                                    <div
                                        className="parallelogram text-center fw-bold bg-white w-100 text-uppercase text-black fw-bold">
                                        <div className="text"><h1>{i18n.t('label.pilot_card')}</h1></div>
                                    </div>
                                    <div className="d-flex w-auto mt-4">
                                        <div
                                            className="parallelogram-inversed justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">{i18n.t('label.callsign')}</div>
                                        </div>
                                        <div
                                            className="parallelogram-inversed justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">{pilot.callsign}</div>
                                        </div>
                                    </div>
                                    <div className="d-flex w-auto mt-4">
                                        <div
                                            className="parallelogram justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">{i18n.t('label.country')}</div>
                                        </div>
                                        <div
                                            className="parallelogram justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">
                                                <span
                                                    className={`flag-icon flag-icon-${pilot?.country ? pilot?.country : pilot?.ipCountry}`}/>
                                                <span className="m-4">
                                                    {pilot.country ? countries[pilot.country.toUpperCase()].name : ''}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-flex w-auto mt-4">
                                        <div
                                            className="parallelogram-inversed justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">{i18n.t('label.best_personal_time')}</div>
                                        </div>
                                        <div
                                            className="parallelogram-inversed justify-content-between d-flex bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                            <div
                                                className="text ml-4 pl-4">{moment.utc((pilot.best ?? 0) * 1000).format('mm:ss.SS')}</div>
                                            <div className="text ml-4 mr-4">{getDeltaTime('time', pilot)}</div>
                                        </div>
                                    </div>
                                    <div className="d-flex w-auto mt-4">
                                        <div
                                            className="parallelogram justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                            <div
                                                className="text ml-4 pl-4">{i18n.t('label.best_qualification_time')}</div>
                                        </div>
                                        <div
                                            className="parallelogram justify-content-between d-flex bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                            <div
                                                className="text ml-4 pl-4">{moment.utc((pilot.bestQualification ?? 0) * 1000).format('mm:ss.SS')}</div>
                                            <div className="text ml-4 mr-4">{getDeltaTime('qualification', pilot)}</div>
                                        </div>
                                    </div>
                                    <div className="d-flex w-auto mt-4">
                                        <div
                                            className="parallelogram-inversed justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">{i18n.t('label.best_stage_time')}</div>
                                        </div>
                                        <div
                                            className="parallelogram-inversed justify-content-between d-flex bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                            <div
                                                className="text ml-4 pl-4">{moment.utc((pilot.time ?? 0) * 1000).format('mm:ss.SS')}</div>
                                            <div className="text ml-4 mr-4">{getDeltaTime('best', pilot)}</div>
                                        </div>
                                    </div>
                                    <div className="d-flex w-auto mt-4">
                                        <div
                                            className="parallelogram justify-content-center flex-grow-1 bg-dark w-50 p-2 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">{i18n.t('label.aircraft')}</div>
                                        </div>
                                        <div
                                            className="parallelogram justify-content-center flex-grow-1 bg-danger p-2 w-50 text-uppercase text-white fw-bold">
                                            <div className="text ml-4 pl-4">DCS: {pilot.plane}</div>
                                        </div>
                                    </div>
                                </div>
                            </React.Fragment>}
                        </Col>
                    </Row>
                </Modal.Body>
            </Modal>
        </>
        <div className="stats__block">
            <div className="stats__title-table">{title}</div>
            <DataGrid key={2} allowColumnReordering={true} dataSource={data} elementAttr={dataGridAttributes}>
                <SearchPanel visible={true} highlightCaseSensitive={true} placeholder={i18n.t('label.search')}/>
                <Column width={40} dataField="ranking" dataType="number" caption={'#'}/>
                <Column dataField="callsign" dataType="string" caption={i18n.t('label.callsign')}
                        cellRender={formatPilot}/>
                <Column dataField="plane" dataType="string" caption={i18n.t('label.aircraft')}/>
                <Column dataField="time" dataType="string" caption={i18n.t('label.time')} customizeText={formatTime}/>
                <Pager allowedPageSizes={pageSizes} showPageSizeSelector={true}/>
                <Paging defaultPageSize={pageSizes[0]}/>
            </DataGrid>
        </div>

    </React.Fragment>;
};

export default PilotsRacingRankingComponent;
