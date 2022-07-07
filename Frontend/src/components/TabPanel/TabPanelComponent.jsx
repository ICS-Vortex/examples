import React from "react";

interface TabPanelProps {
    children?: React.ReactNode;
    index;
    value;
    identifier;
}

const TabPanelComponent<TabPanelProps> = (props: TabPanelProps) => {

    const {children, value, index, identifier, ...other} = props;

    return (
        <div role="tabpanel"
             hidden={value !== index}
             id={`${identifier}-${index}`}
             aria-labelledby={`${identifier}-tab-${index}`}
             {...other}
        >
            {value === index && (
                <div></div>
                // <Box p={1}>
                //     <Typography component="span">{children}</Typography>
                // </Box>
            )}
        </div>
    );
}

export default TabPanelComponent;
