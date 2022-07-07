import React, {useEffect} from 'react';
import i18next from "../../i18n";
import moment from 'moment';

const ServerInfoComponent = ({server}) => {
    let interval = null;

    const [elapsedTime, setElapsedTime] = React.useState(0);

    const formatTime = (seconds) => {
        return moment.utc(seconds * 1000).format('HH:mm:ss');
    };

    const update = () => {
        setElapsedTime(prevState => prevState + 1);
    };

    useEffect(() => {
        if (server !== null && server?.currentMissionRegistry !== null) {
            setElapsedTime(moment(server?.lastActivity).diff(moment(server?.currentMissionRegistry?.start), 'seconds'));
            interval = setInterval(update, 1000);
        }

        return () => {
            clearInterval(interval);
        };
    }, [server?.currentMissionRegistry]);

    return (
        <div className="about-mission">
            <div className="about-mission__group">
                <div className="about-mission__label">
                    {server?.isOnline ? i18next.t('label.current_mission') : i18next.t('label.last_mission')} :
                </div>
                <div className="about-mission__value">{server?.currentMissionRegistry?.mission?.name}</div>
            </div>
            {server?.isOnline && <div className="about-mission__group">
                <div className="about-mission__label">{i18next.t('label.elapsed_time')}:</div>
                <div className="about-mission__value">{formatTime(elapsedTime)}</div>
            </div>}
            <div className="about-mission__group">
                <div className="about-mission__label">{i18next.t('label.map')}:</div>
                <div className="about-mission__value">{server?.currentMissionRegistry?.theatre?.name}</div>
            </div>

            {server?.isOnline && <div className="about-mission__weather weather">
                {<div className="weather__col">
                    <div className="weather__season">
                        {/*<img src="/images/winter-icon.png" alt=""/>*/}
                        {/*ЗИМА*/}
                    </div>
                    <div className="weather__temperature">
                        {server?.currentMissionRegistry?.temperature} °C
                    </div>
                </div>}
                <div className="weather__col">
                    <div className="weather__desc">
                        <p>{i18next.t('label.windspeed_at_ground')}: {server?.currentMissionRegistry?.windSpeedAtGround} {i18next.t('label.meters_per_second')}</p>
                        <p>{i18next.t('label.visibility_distance')}: {(server?.currentMissionRegistry?.visibilityDistance / 1000)} {i18next.t('label.km')}</p>
                        <p>{i18next.t('label.qnh')}: {server?.currentMissionRegistry?.qnh}</p>
                    </div>
                </div>
            </div>}
        </div>
    );
};

export default ServerInfoComponent;