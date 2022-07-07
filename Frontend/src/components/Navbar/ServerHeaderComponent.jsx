import React from 'react';
import i18next from "i18next";
import {Modal} from "react-bootstrap";
import {Link} from 'react-router-dom';
import i18n from "../../i18n";

const ServerHeaderComponent = ({server}) => {
    const [show, setShow] = React.useState(false);
    const isBeta = server.beta;
    const handleClose = () => setShow(false);

    return (
        <div className="main__head">
            <div className="single-server">
                <div className="single-server__thumb">
                    <img
                        src={server.backgroundImage ? process.env.REACT_APP_API_HOST + '/uploads/images/servers/' + server.backgroundImage : process.env.REACT_APP_API_HOST + '/images/cover.jpg'}
                        alt=""/>
                </div>
                <div className="single-server__name">
                    <Link to={`/server/${server?.id}/home`}
                          className="text-decoration-none">{server.name}</Link>
                </div>
                <div className="single-server__stars">
                    {server.beta && <span className="badge badge-danger"/>}
                    {!server.beta && <span className="badge badge-succes"/>}
                    {/*<ul className="stars">*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*</ul>*/}
                </div>
                {server.isOnline && <div
                    className="single-server__status single-server__status_online text-uppercase">
                    {i18next.t('status.online')}
                </div>}
                {!server.isOnline && <div className="single-server__status text-uppercase">
                    {i18next.t('status.offline')}
                </div>}
                {server.isOnline && <div onClick={() => {
                    setShow(true)
                }} className="single-server__plan pointer">{server.pilotsOnline?.length}</div>}
                <div className="single-server__dcs">
                    DCS World: {server.version}
                    <span
                        className={`badge m-3 ${isBeta ? 'bg-danger' : 'bg-success'}`}>{i18n.t((isBeta == true) ? 'label.beta' : 'label.release')}</span>
                </div>
            </div>

            <Modal show={show} onHide={handleClose} keyboard={true} centered>
                <Modal.Body>
                    <div className="stats__block">
                        <div className="stats__title-table">{i18next.t('label.pilots_online')}</div>
                        <table className="table text-white">
                            <thead>
                            <tr>
                                <td>{i18next.t('label.country')}</td>
                                <td>{i18next.t('label.callsign')}</td>
                                <td>{i18next.t('label.aircraft')}</td>
                            </tr>
                            </thead>
                            <tbody>
                            {server.pilotsOnline?.map((pilot, i) => (
                                <tr key={i}>
                                    <td><span
                                        className={`flag-icon flag-icon-${pilot.pilot?.ipCountry?.toLowerCase()}`}/>
                                    </td>
                                    <td>
                                        {pilot.side === 'RED' &&
                                        <a href={`/pilot/${pilot.pilot?.id}?server=${server.id}`}
                                           className="text-danger text-decoration-none">{pilot.pilot?.username}</a>}
                                        {pilot.side === 'BLUE' &&
                                        <a href={`/pilot/${pilot.pilot?.id}?server=${server.id}`}
                                           className="text-primary text-decoration-none">{pilot.pilot?.username}</a>}
                                        {pilot.side !== 'RED' && pilot.side !== 'BLUE' &&
                                        <a href={`/pilot/${pilot.pilot?.id}?server=${server.id}`}
                                           className="text-white text-decoration-none">{pilot.pilot?.username}</a>}
                                    </td>
                                    <td>{pilot.plane?.name}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>
                </Modal.Body>
            </Modal>
        </div>
    );
}

export default ServerHeaderComponent;
