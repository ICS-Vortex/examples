import React, {useEffect} from 'react';
import i18next from '../../i18n';
import {URL_API_SERVERS} from "../../constants/urls";
import history from "../../history";

const PilotsRankingTableComponent = ({server, tour}) => {
    const [pilots, setPilots] = React.useState([]);
    const eloCellFormatter = (cell, pilot) => {
        const cName = pilot.bestEloParam === 'RED' ? 'bg-danger' : 'bg-primary';
        return (
            <div className={`${cName}`}>{pilot.bestElo}</div>
        );
    };

    const countryCellFormatter = (cell, pilot) => {
        return (
            <span title={pilot.country?.toUpperCase()} className={`flag-icon flag-icon-${pilot.country}`}/>
        );
    };

    const headerCellFormatter = (column, index) => {
        let alt;
        let title;
        let image;
        switch (column.dataField) {
            case 'flightTime':
                image = 'clock.png';
                title = i18next.t('label.flight_time');
                alt = i18next.t('label.flight_time');
                break;
            case 'country':
                return <><i className="fa fa-globe fa-2x"/>{column.sort && <span className="order-4"/>}</>;
            case 'callsign':
                return <><i className="fa fa-user fa-2x"/>{column.sort && <span className="order-4"/>}</>;
            case 'sorties':
                image = 'takeoff.png';
                title = i18next.t('label.takeoffs');
                alt = i18next.t('label.takeoffs');
                break;
            case 'landings':
                image = 'landing.png';
                title = i18next.t('label.landings');
                alt = i18next.t('label.landings');
                break;
            case 'airBattles':
                image = 'crosshairs.png';
                title = i18next.t('label.air_battles');
                alt = i18next.t('label.air_battles');
                break;
            case 'airWins':
                image = 'star.png';
                title = i18next.t('label.air_wins');
                alt = i18next.t('label.air_wins');
                break;
            case 'groundKills':
                image = 'fire.png';
                title = i18next.t('label.ground_dogfights');
                alt = i18next.t('label.ground_dogfights');
                break;
            case 'bestElo':
                image = 'star.png';
                title = i18next.t('label.elo');
                alt = i18next.t('label.elo');
                break;
            case 'died':
                return <>
                    <i title={i18next.t('label.died')} className="fa fa-briefcase-medical fa-2x"/>
                    {column.sort && <span className="order-4"/>}
                </>;
        }
        return <><img src={`/images/icons/${image}`} title={title} alt={alt}/>{column.sort &&
        <span className="order-4"/>}</>;
    };

    const columns = [
        {
            dataField: 'country',
            text: i18next.t('label.country'),
            sort: true,
            formatter: countryCellFormatter,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'callsign',
            text: i18next.t('label.callsign'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'flightTime',
            text: i18next.t('label.flight_time'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'sorties',
            text: i18next.t('label.takeoffs'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'landings',
            text: i18next.t('label.landings'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'airBattles',
            text: i18next.t('label.air_battles'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'airWins',
            text: i18next.t('label.air_wins'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'groundKills',
            text: i18next.t('label.ground_dogfights'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'died',
            text: i18next.t('label.died'),
            sort: true,
            headerFormatter: headerCellFormatter
        },
        {
            dataField: 'bestElo',
            text: i18next.t('label.elo'),
            sort: true,
            formatter: eloCellFormatter,
            headerFormatter: headerCellFormatter
        },

    ];

    const defaultSorted = [{
        dataField: 'name',
        order: 'desc'
    }];
    useEffect(() => {
        if (server.id) {
            fetch(URL_API_SERVERS + `/${server.id}/pilots-pvp-ranking?tour=${tour}`)
                .then(r => r.json())
                .then(data => {
                    setPilots(data);
                })
            ;
        }
    }, [server, tour]);

    const handleClick = (pilot) => {
        history.push(`/pilot/${pilot.id}?server=${server.id}`, {server: server});
    };

    return <React.Fragment>
        <div className="pilot-rating">
            <div className="pilot-rating__title">{i18next.t('label.fighters_ranking')}</div>
            <div className="pilot-rating__table">
                {/*<BootstrapTable bootstrap4 classes="rating-table" bordered={false}*/}
                {/*                keyField="id" data={pilots}*/}
                {/*                columns={columns} pagination={paginationFactory({})}*/}
                {/*/>*/}
            </div>
        </div>
    </React.Fragment>;
};

export default PilotsRankingTableComponent;