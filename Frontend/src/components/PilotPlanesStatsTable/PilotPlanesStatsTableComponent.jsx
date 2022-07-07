import React, {useEffect} from 'react';

const PilotPlanesStatsTableComponent = ({pilot, server, tour}) => {
    const [planeStats, setPlaneStats] = React.useState(new Array(0));
    const url = process.env.REACT_APP_API_HOST + `/api/open/pilots/${pilot}/planes-stats?server=${server}&tour=${tour}`;

    useEffect(() => {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                setPlaneStats(data);
            }).finally(() => {
        });
    }, []);

    return (<React.Fragment>
        {/*<Typography variant="h5">*/}
        {/*    {i18next.t('label.flights')}*/}
        {/*</Typography>*/}
        {/*<TableContainer>*/}
        {/*    <Table>*/}
        {/*        <TableHead>*/}
        {/*            <TableRow>*/}
        {/*                <TableCell>{i18next.t('label.aircraft')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.time')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.takeoffs')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.landings')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.dogfights')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.air_wins')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.destroyed')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.ground_score')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.total_score')}</TableCell>*/}
        {/*                <TableCell>{i18next.t('label.died')}</TableCell>*/}
        {/*            </TableRow>*/}
        {/*        </TableHead>*/}
        {/*        <TableBody>*/}
        {/*            {planeStats.length > 0 && planeStats.map((row: PilotPlaneStats, i) => (*/}
        {/*                <TableRow key={i}>*/}
        {/*                    <TableCell>*/}
        {/*                        <Tooltip title={row.name}>*/}
        {/*                            <img width={50}*/}
        {/*                                 src={`${process.env.REACT_APP_API_HOST}/images/planes/${row.name.toLowerCase()}.png`}*/}
        {/*                                 alt={row.name}/>*/}
        {/*                        </Tooltip>*/}
        {/*                    </TableCell>*/}
        {/*                    <TableCell>{row.totalTime}</TableCell>*/}
        {/*                    <TableCell>{row.takeoffs}</TableCell>*/}
        {/*                    <TableCell>{row.landings}</TableCell>*/}
        {/*                    <TableCell>{parseInt(row.airkills) + parseInt(row.looses)}</TableCell>*/}
        {/*                    <TableCell>{row.airkills}</TableCell>*/}
        {/*                    <TableCell>{row.destroyed}</TableCell>*/}
        {/*                    <TableCell>{row.groundPoints}</TableCell>*/}
        {/*                    <TableCell>{parseInt(row.airPoints) + parseInt(row.groundPoints)}</TableCell>*/}
        {/*                    <TableCell>{row.died}</TableCell>*/}
        {/*                </TableRow>*/}
        {/*            ))}*/}
        {/*        </TableBody>*/}
        {/*    </Table>*/}
        {/*</TableContainer>*/}
    </React.Fragment>);
}

export default PilotPlanesStatsTableComponent;