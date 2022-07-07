import React from "react";
import history from "../../history";
import './ServerComponent.module.css';
import i18next from '../../i18n';
import {Badge, Modal} from "react-bootstrap";
import {Link} from "react-router-dom";

const ServerComponent = ({server}) => {
    const [show, setShow] = React.useState(false);

    const handleClose = () => {
        setShow(false);
    };
    const handleOpen = () => {
        setShow(true);
    };

    const handleClick = () => {
        history.push('/server/' + server.id, {server: server});
    };
    const getServerLink = (server) => {
        return `/server/${server.id}/stats`;
    };

    const getPilotsOnlineCount = (server) => {
        if (server.pilotsOnline !== null && server.pilotsOnline !== undefined) {
            return server.pilotsOnline.length;

        }
        return 0;
    };

    return <React.Fragment>
        <div className="server">
            <Modal show={show} onHide={handleClose} keyboard={true} centered>
                <Modal.Body>
                    <div className="stats__block">
                        <div className="stats__title-table">{i18next.t('label.pilots_online')} ({server.name})</div>
                        <table className="table text-white">
                            <thead>
                            <tr>
                                <td>{i18next.t('label.callsign')}</td>
                                <td>{i18next.t('label.aircraft')}</td>
                            </tr>
                            </thead>
                            <tbody>
                            {server.pilotsOnline?.map((pilot, i) => (
                                <tr key={i}>
                                    <td>
                                        <span className={`m-1 flag-icon flag-icon-${pilot.pilot?.country ? pilot.pilot.country?.toLowerCase() : pilot?.pilot?.ipCountry?.toLowerCase() }`}/>

                                        {pilot.side === 'RED' &&
                                        <Link to={`/pilot/${pilot.pilot?.id}?server=${server.id}`}
                                              className="text-danger text-decoration-none">{pilot.pilot?.username}</Link>}
                                        {pilot.side === 'BLUE' &&
                                        <Link to={`/pilot/${pilot.pilot?.id}?server=${server.id}`}
                                              className="text-primary text-decoration-none">{pilot.pilot?.username}</Link>}
                                        {pilot.side !== 'RED' && pilot.side !== 'BLUE' &&
                                        <Link to={`/pilot/${pilot.pilot?.id}?server=${server.id}`}
                                              className="text-white text-decoration-none">{pilot.pilot?.username}</Link>}
                                    </td>
                                    <td>{pilot.plane?.name}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>
                </Modal.Body>
            </Modal>
            <Link to={getServerLink(server)} className="server__thumb">
                <img
                    src={server.backgroundImage ? process.env.REACT_APP_API_HOST + '/uploads/images/servers/' + server.backgroundImage : '/images/cover.jpg'}
                    alt=""/>
            </Link>
            <div className="server__body">
                <Link to="#" className="server__title text-decoration-none">{server.name}</Link>
                {!server.isOnline && <div className="server__status">{i18next.t('server.offline')}</div>}
                {server.isOnline && <>
                    <div
                        className="text-white server__status server__status_online">{i18next.t('server.online')}</div>
                    <div className="text-white server__plane pointer" onClick={handleOpen}>
                        {getPilotsOnlineCount(server)}
                    </div>
                    <div className="text-white server__dcs">
                        DCS World: {server.version}

                    </div>
                </>}
            </div>
            <div className="server__bottom">
                <div className="server__stars">
                    <div className="stars">
                        {server.beta && <Badge className="badge bg-danger">{i18next.t('label.beta')}</Badge>}
                        {!server.beta &&
                        <Badge className="badge bg-success">{i18next.t('label.release')}</Badge>}
                    </div>
                    {/*<ul className="stars">*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*<li/>*/}
                    {/*</ul>*/}
                </div>
                <div className="server__button">
                    <Link to={getServerLink(server)}
                          className="button-more">{i18next.t('button.details')}</Link>
                </div>
            </div>
        </div>
    </React.Fragment>;
}

export default ServerComponent;

