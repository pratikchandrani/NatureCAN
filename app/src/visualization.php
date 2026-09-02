<?php
session_start();
include("db_config_test.php");
error_reporting(0);
include('header.php');
include('header_navbar.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>NatureCAN - Data Visualization</title>
    <style>
        .viz-container {
            width: 100%;
            min-height: 100vh;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="viz-container">
        <div id="root"></div>
    </div>

    <!-- React and Recharts from CDN -->
    <script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://unpkg.com/recharts@2.5.0/dist/Recharts.js"></script>

    <script type="text/babel">
        const { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } = Recharts;
        const { useState, useEffect } = React;

        const PlantCancerStudyChart = () => {
          const [data, setData] = useState([]);
          const [loading, setLoading] = useState(true);
          const [error, setError] = useState(null);
          const [sortBy, setSortBy] = useState('plant');
          const [topN, setTopN] = useState(10);

          useEffect(() => {
            const fetchData = async () => {
              try {
                const response = await fetch('get_plant_stats.php');
                const result = await response.json();
                
                // Process the data to count unique cancer types and study types
                const processedData = result.map(item => {
                  const cancerTypes = item.cancer_types ? item.cancer_types.split(',').map(s => s.trim()).filter(s => s) : [];
                  const studyTypes = item.study_types ? item.study_types.split(',').map(s => s.trim()).filter(s => s) : [];
                  
                  return {
                    name: item.plant_name,
                    'Unique Cancer Types': new Set(cancerTypes).size,
                    'Unique Study Types': new Set(studyTypes).size,
                    cancerTypesList: [...new Set(cancerTypes)],
                    studyTypesList: [...new Set(studyTypes)]
                  };
                });

                setData(processedData);
                setLoading(false);
              } catch (err) {
                setError('Error loading data: ' + err.message);
                setLoading(false);
              }
            };

            fetchData();
          }, []);

          const getSortedData = () => {
            let sorted = [...data];
            
            switch(sortBy) {
              case 'cancer':
                sorted.sort((a, b) => b['Unique Cancer Types'] - a['Unique Cancer Types']);
                break;
              case 'study':
                sorted.sort((a, b) => b['Unique Study Types'] - a['Unique Study Types']);
                break;
              case 'total':
                sorted.sort((a, b) => 
                  (b['Unique Cancer Types'] + b['Unique Study Types']) - 
                  (a['Unique Cancer Types'] + a['Unique Study Types'])
                );
                break;
              default:
                break;
            }
            
            return sorted.slice(0, topN);
          };

          const CustomTooltip = ({ active, payload }) => {
            if (active && payload && payload.length) {
              const data = payload[0].payload;
              return React.createElement('div', {
                style: {
                  backgroundColor: 'white',
                  padding: '15px',
                  border: '2px solid #20558a',
                  borderRadius: '8px',
                  boxShadow: '0 4px 6px rgba(0,0,0,0.1)'
                }
              },
                React.createElement('p', { style: { fontWeight: 'bold', color: '#20558a', marginBottom: '8px' } }, data.name),
                React.createElement('p', { style: { fontSize: '14px', color: '#22c55e' } },
                  React.createElement('span', { style: { fontWeight: '600' } }, 'Cancer Types: '),
                  data['Unique Cancer Types']
                ),
                React.createElement('p', { style: { fontSize: '12px', color: '#666', marginBottom: '8px' } },
                  data.cancerTypesList.join(', ')
                ),
                React.createElement('p', { style: { fontSize: '14px', color: '#8b5cf6' } },
                  React.createElement('span', { style: { fontWeight: '600' } }, 'Study Types: '),
                  data['Unique Study Types']
                ),
                React.createElement('p', { style: { fontSize: '12px', color: '#666' } },
                  data.studyTypesList.join(', ')
                )
              );
            }
            return null;
          };

          if (loading) {
            return React.createElement('div', {
              style: {
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                height: '100vh',
                background: 'linear-gradient(to bottom right, #eff6ff, #f0fdf4)'
              }
            },
              React.createElement('div', { style: { textAlign: 'center' } },
                React.createElement('div', {
                  style: {
                    width: '64px',
                    height: '64px',
                    border: '4px solid #20558a',
                    borderTopColor: 'transparent',
                    borderRadius: '50%',
                    animation: 'spin 1s linear infinite',
                    margin: '0 auto 16px'
                  }
                }),
                React.createElement('p', { style: { color: '#20558a', fontWeight: '600' } }, 'Loading data...')
              )
            );
          }

          if (error) {
            return React.createElement('div', {
              style: {
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                height: '100vh'
              }
            },
              React.createElement('div', {
                style: {
                  backgroundColor: 'white',
                  padding: '24px',
                  borderRadius: '8px',
                  boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
                  border: '2px solid #ef4444'
                }
              },
                React.createElement('p', { style: { color: '#dc2626', fontWeight: '600' } }, error)
              )
            );
          }

          const chartData = getSortedData();

          return React.createElement('div', {
            style: {
              minHeight: '100vh',
              background: 'linear-gradient(to bottom right, #eff6ff, #f0fdf4)',
              padding: '24px'
            }
          },
            React.createElement('div', { style: { maxWidth: '1280px', margin: '0 auto' } },
              React.createElement('div', {
                style: {
                  backgroundColor: 'white',
                  borderRadius: '8px',
                  boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
                  padding: '24px',
                  marginBottom: '24px'
                }
              },
                React.createElement('h1', {
                  style: {
                    fontSize: '28px',
                    fontWeight: 'bold',
                    color: '#20558a',
                    marginBottom: '8px'
                  }
                }, 'NatureCAN Database Analysis'),
                React.createElement('p', {
                  style: { color: '#666', marginBottom: '24px' }
                }, 'Unique Cancer Types and Study Types by Plant Name'),

                // Controls
                React.createElement('div', {
                  style: {
                    display: 'flex',
                    flexWrap: 'wrap',
                    gap: '16px',
                    marginBottom: '24px',
                    alignItems: 'center'
                  }
                },
                  React.createElement('div', { style: { display: 'flex', alignItems: 'center', gap: '8px' } },
                    React.createElement('label', {
                      style: { fontSize: '14px', fontWeight: '600', color: '#374151' }
                    }, 'Sort by:'),
                    React.createElement('select', {
                      value: sortBy,
                      onChange: (e) => setSortBy(e.target.value),
                      style: {
                        border: '2px solid #20558a',
                        borderRadius: '4px',
                        padding: '8px 12px',
                        fontSize: '14px'
                      }
                    },
                      React.createElement('option', { value: 'plant' }, 'Plant Name'),
                      React.createElement('option', { value: 'cancer' }, 'Cancer Types (High to Low)'),
                      React.createElement('option', { value: 'study' }, 'Study Types (High to Low)'),
                      React.createElement('option', { value: 'total' }, 'Total Count (High to Low)')
                    )
                  ),
                  React.createElement('div', { style: { display: 'flex', alignItems: 'center', gap: '8px' } },
                    React.createElement('label', {
                      style: { fontSize: '14px', fontWeight: '600', color: '#374151' }
                    }, 'Show top:'),
                    React.createElement('select', {
                      value: topN,
                      onChange: (e) => setTopN(Number(e.target.value)),
                      style: {
                        border: '2px solid #20558a',
                        borderRadius: '4px',
                        padding: '8px 12px',
                        fontSize: '14px'
                      }
                    },
                      React.createElement('option', { value: '5' }, '5'),
                      React.createElement('option', { value: '10' }, '10'),
                      React.createElement('option', { value: '15' }, '15'),
                      React.createElement('option', { value: '20' }, '20'),
                      React.createElement('option', { value: data.length }, `All (${data.length})`)
                    )
                  )
                ),

                // Chart
                React.createElement(ResponsiveContainer, { width: '100%', height: 500 },
                  React.createElement(BarChart, {
                    data: chartData,
                    margin: { top: 20, right: 30, left: 20, bottom: 100 }
                  },
                    React.createElement(CartesianGrid, { strokeDasharray: '3 3', stroke: '#e0e0e0' }),
                    React.createElement(XAxis, {
                      dataKey: 'name',
                      angle: -45,
                      textAnchor: 'end',
                      height: 150,
                      interval: 0,
                      tick: { fontSize: 12, fill: '#333' }
                    }),
                    React.createElement(YAxis, {
                      label: {
                        value: 'Count',
                        angle: -90,
                        position: 'insideLeft',
                        style: { fontSize: 14, fontWeight: 'bold' }
                      },
                      tick: { fontSize: 12 }
                    }),
                    React.createElement(Tooltip, { content: React.createElement(CustomTooltip) }),
                    React.createElement(Legend, {
                      wrapperStyle: { paddingTop: '20px' },
                      iconType: 'rect'
                    }),
                    React.createElement(Bar, {
                      dataKey: 'Unique Cancer Types',
                      fill: '#22c55e',
                      radius: [8, 8, 0, 0],
                      maxBarSize: 80
                    }),
                    React.createElement(Bar, {
                      dataKey: 'Unique Study Types',
                      fill: '#8b5cf6',
                      radius: [8, 8, 0, 0],
                      maxBarSize: 80
                    })
                  )
                ),

                // Summary Statistics
                React.createElement('div', {
                  style: {
                    marginTop: '32px',
                    display: 'grid',
                    gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
                    gap: '16px'
                  }
                },
                  React.createElement('div', {
                    style: {
                      backgroundColor: '#f0fdf4',
                      border: '2px solid #22c55e',
                      borderRadius: '8px',
                      padding: '16px'
                    }
                  },
                    React.createElement('h3', {
                      style: { fontSize: '14px', fontWeight: '600', color: '#374151', marginBottom: '8px' }
                    }, 'Total Plants'),
                    React.createElement('p', {
                      style: { fontSize: '28px', fontWeight: 'bold', color: '#16a34a' }
                    }, data.length)
                  ),
                  React.createElement('div', {
                    style: {
                      backgroundColor: '#eff6ff',
                      border: '2px solid #3b82f6',
                      borderRadius: '8px',
                      padding: '16px'
                    }
                  },
                    React.createElement('h3', {
                      style: { fontSize: '14px', fontWeight: '600', color: '#374151', marginBottom: '8px' }
                    }, 'Avg Cancer Types/Plant'),
                    React.createElement('p', {
                      style: { fontSize: '28px', fontWeight: 'bold', color: '#2563eb' }
                    }, (data.reduce((sum, item) => sum + item['Unique Cancer Types'], 0) / data.length).toFixed(1))
                  ),
                  React.createElement('div', {
                    style: {
                      backgroundColor: '#faf5ff',
                      border: '2px solid #8b5cf6',
                      borderRadius: '8px',
                      padding: '16px'
                    }
                  },
                    React.createElement('h3', {
                      style: { fontSize: '14px', fontWeight: '600', color: '#374151', marginBottom: '8px' }
                    }, 'Avg Study Types/Plant'),
                    React.createElement('p', {
                      style: { fontSize: '28px', fontWeight: 'bold', color: '#7c3aed' }
                    }, (data.reduce((sum, item) => sum + item['Unique Study Types'], 0) / data.length).toFixed(1))
                  )
                )
              )
            )
          );
        };

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(React.createElement(PlantCancerStudyChart));
    </script>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <?php include("footer.php"); ?>
</body>
</html>