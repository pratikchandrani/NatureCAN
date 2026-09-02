import pandas as pd

# Load file
# df = pd.read_csv("/content/naturecan_data_2026-01-12.csv")#, sheet_name="naturecan_data_2026-01-12") at home
df = pd.read_excel("/content/merged_output_D_20251225 (6).xlsx",sheet_name="Main_13922")#, Modified - 15/01/2026)

print("Columns - ",df.columns)
# Column containing cancer types
col = "Plant_Name"   # change if needed

# Drop NaN and split by comma
all_types = (
    df[col]
    .dropna()
    .apply(lambda x: [p.strip().title() for p in x.split(',')])
)

# Flatten the list
flattened = [item for sublist in all_types for item in sublist]

# Count frequencies
cancer_counts_df = (
    pd.Series(flattened)
    .value_counts()
    .reset_index()
)

cancer_counts_df.columns = ['Plant Names', 'Count']
print("--------------------------------------")
print(cancer_counts_df)
