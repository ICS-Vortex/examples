import React, {useEffect} from 'react';
import i18next from '../../i18n';
import {URL_API_SERVERS} from "../../constants/urls";

const PilotsPlanesPveRankingTableComponent = ({server, tour}) => {
    const [ranking, setRanking] = React.useState<[]>([]);
    const options = {
        filterType: 'checkbox',
        exportButton: false,
        draggable: false,
        print: false,
        download: false,
        sortFilterList: false,
        selectableRows: false,
        resizableColumns: false,
        responsive: 'scroll'
    };

    const columns = [
        {
            name: i18next.t('label.country'), field: "country", options: {
                headerNoWrap: true,
                customBodyRender: (rank) => {
                    return rank.country?.toUpperCase();
                }
            },
        },
        {
            name: i18next.t('label.callsign'), field: "callsign", options: {
                headerNoWrap: true,
                filter: false,
                customBodyRender: (rank) => {
                    return <div>
                        <span className={`flag-icon flag-icon-${rank.country ? rank.country.toLowerCase() : ''}`}/>
                        <a className={`text-blue`}
                           href={`/pilot/${rank.id}?server=${server?.id}&tour=${tour}`}>{rank.callsign}</a>
                    </div>;
                }
            },
        },
        {name: i18next.t('label.plane'), field: "plane", options: {filter: true}},
        {name: i18next.t('label.flight_time'), field: "flightTime", options: {filter: false}},
        {name: i18next.t('label.sorties'), field: "takeoffs", options: {filter: false}},
        {name: i18next.t('label.landings'), field: "landings", options: {filter: false}},
        {name: i18next.t('label.ai_kills'), field: "aiKills", options: {filter: false}},
        {name: i18next.t('label.ground_kills'), field: "groundKills", options: {filter: false}},
        {name: i18next.t('label.sea_kills'), field: "seaKills", options: {filter: false}},
        {name: i18next.t('label.died'), field: "died", options: {filter: false}},
        {name: i18next.t('label.score'), field: "score", options: {filter: false}}

    ];

    useEffect(() => {
        if (server) {
            fetch(URL_API_SERVERS + `/${server.id}/pilots-planes-pve-ranking?tour=${tour}`)
                .then(r => r.json())
                .then(data => {
                    setRanking(data);
                })
            ;
        }
    }, [server, tour]);

    return <React.Fragment>
        {/*<MaterialDatatable*/}
        {/*    title={server?.name + ' - ' + i18next.t('label.players_slash_planes')}*/}
        {/*    data={ranking}*/}
        {/*    columns={columns}*/}
        {/*    options={options}*/}
        {/*/>*/}

    </React.Fragment>;
};

export default PilotsPlanesPveRankingTableComponent;